import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Quote from '@editorjs/quote';
import CodeTool from '@editorjs/code';
import InlineCode from '@editorjs/inline-code';
import Embed from '@editorjs/embed';
import ImageTool from '@editorjs/image';

const editorHolder = document.getElementById('post-editor');
const postForm = document.getElementById('post-form');
const bodyInput = document.getElementById('body');

if (editorHolder && postForm && bodyInput) {
    class SocialEmbed extends Embed {
        static get toolbox() {
            return {
                title: 'Embed',
                icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M10 8H7.5C5.567 8 4 9.567 4 11.5C4 13.433 5.567 15 7.5 15H10M14 8H16.5C18.433 8 20 9.567 20 11.5C20 13.433 18.433 15 16.5 15H14M8.5 11.5H15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            };
        }

        render() {
            if (this.data.service) {
                return super.render();
            }

            const wrapper = document.createElement('div');
            const input = document.createElement('input');
            const helpText = document.createElement('p');

            wrapper.className = 'rounded-[18px] border border-[#dedfe4] bg-white p-4';
            input.type = 'url';
            input.placeholder = 'Paste an X/Twitter, Instagram or Facebook URL';
            input.className = 'min-h-12 w-full rounded-[14px] border border-[#dedfe4] px-4 text-base font-medium text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]';
            helpText.className = 'mt-2 text-sm font-semibold text-[#8b9099]';
            helpText.textContent = 'Press Enter after pasting the supported social post URL.';

            const convertInput = () => {
                const wasConverted = this.convertUrl(input.value.trim());

                if (! wasConverted) {
                    helpText.className = 'mt-2 text-sm font-semibold text-[#ef233c]';
                    helpText.textContent = 'Use a supported X/Twitter, Instagram or Facebook post URL.';
                }
            };

            input.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    convertInput();
                }
            });

            input.addEventListener('paste', () => {
                window.setTimeout(convertInput, 0);
            });

            wrapper.append(input, helpText);
            this.element = wrapper;

            return wrapper;
        }

        convertUrl(url) {
            const services = this.constructor.services || Embed.services || {};

            for (const [service, config] of Object.entries(services)) {
                const matches = config.regex.exec(url);

                if (! matches) {
                    continue;
                }

                const groups = matches.slice(1);
                const remoteId = config.id
                    ? config.id(groups)
                    : groups.shift();

                this.data = {
                    service,
                    source: url,
                    embed: config.embedUrl.replace(/<%= remote_id %>/g, remoteId),
                    width: config.width,
                    height: config.height,
                    caption: '',
                };

                return true;
            }

            return false;
        }
    }

    let existingData = {};
    const shouldAutofocus = editorHolder.dataset.autofocus !== 'false';

    try {
        // Load existing Editor.js JSON on the edit page.
        existingData = bodyInput.value
            ? JSON.parse(bodyInput.value)
            : {};
    } catch (error) {
        console.error('Unable to load existing blog content.', error);
    }

    const editor = new EditorJS({
        holder: 'post-editor',
        autofocus: shouldAutofocus,

        // Existing content appears here when editing a post.
        data: existingData,

        tools: {
            header: {
                class: Header,
                inlineToolbar: true,
            },

            list: {
                class: List,
                inlineToolbar: true,
            },

            quote: {
                class: Quote,
                inlineToolbar: true,
            },

            code: CodeTool,

            inlineCode: {
                class: InlineCode,
                shortcut: 'CMD+SHIFT+M',
            },

            embed: {
                class: SocialEmbed,
                inlineToolbar: true,
                config: {
                    services: {
                        twitter: true,
                        instagram: {
                            regex: /^https?:\/\/(?:www\.)?instagram\.com\/(p|reel)\/([^/?#&]+)/,
                            embedUrl: 'https://www.instagram.com/<%= remote_id %>/embed',
                            html: '<iframe width="540" height="620" style="margin: 0 auto;" frameborder="0" scrolling="no" allowtransparency="true"></iframe>',
                            height: 620,
                            width: 540,
                            id: (groups) => `${groups[0]}/${groups[1]}`,
                        },
                        facebook: {
                            regex: /^(https?:\/\/(?:www\.)?facebook\.com\/(?:(?:[^/?#]+\/posts\/[^/?#]+)|(?:permalink\.php\?story_fbid=[^#\s]+)|(?:share\/p\/[^/?#\s]+\/?)|(?:reel\/[^/?#\s]+\/?)|(?:share\/r\/[^/?#\s]+\/?)|(?:watch\/?\?v=[^&#\s]+)|(?:[^/?#]+\/videos\/[^/?#\s]+\/?))(?:[?#][^\s]*)?)$/,
                            embedUrl: 'https://www.facebook.com/plugins/<%= remote_id %>',
                            html: '<iframe width="500" height="560" style="margin: 0 auto;" frameborder="0" scrolling="no" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>',
                            height: 560,
                            width: 500,
                            id: (groups) => {
                                const sourceUrl = groups[0];
                                const isVideo = /facebook\.com\/(?:reel|share\/r|watch|[^/?#]+\/videos)/.test(sourceUrl);
                                const plugin = isVideo ? 'video.php' : 'post.php';
                                const showText = isVideo ? 'false' : 'true';

                                return `${plugin}?href=${encodeURIComponent(sourceUrl)}&width=500&show_text=${showText}`;
                            },
                        },
                    },
                },
            },

            image: {
                class: ImageTool,
                config: {
                    endpoints: {
                        byFile: '/admin/posts/images',
                    },
                    field: 'image',
                    captionPlaceholder: 'Add an image caption...',
                    additionalRequestHeaders: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute('content'),
                    },
                },
            },
        },

        placeholder: 'Write your blog post...',
    });

    postForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        try {
            // Save the latest Editor.js blocks into the hidden body field.
            const outputData = await editor.save();

            bodyInput.value = JSON.stringify(outputData);

            postForm.submit();
        } catch (error) {
            console.error('Unable to save the blog content.', error);
            alert('The blog content could not be saved. Please try again.');
        }
    });
}
