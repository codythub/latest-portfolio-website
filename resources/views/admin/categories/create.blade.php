<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Add Category</title>
</head>

<body>

    <header>
        <a href="{{ route('admin.categories.index') }}">
            Back to categories
        </a>
    </header>

    <main>
        <h1>Add Category</h1>

        <form
            method="POST"
            action="{{ route('admin.categories.store') }}"
        >
            @csrf

            <div>
                <label for="name">Category name</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                >

                @error('name')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <br>

            <div>
                <label for="type">Category type</label>

                <select
                    id="type"
                    name="type"
                    required
                >
                    <option value="">Select category type</option>

                    <option
                        value="project"
                        @selected(old('type') === 'project')
                    >
                        Project
                    </option>

                    <option
                        value="blog"
                        @selected(old('type') === 'blog')
                    >
                        Blog
                    </option>
                </select>

                @error('type')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <br>

            <div>
                <label for="display_order">Display order</label>

                <input
                    type="number"
                    id="display_order"
                    name="display_order"
                    value="{{ old('display_order', 0) }}"
                    min="0"
                    required
                >

                @error('display_order')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <br>

            <div>
                <label>
                    <input
                        type="checkbox"
                        name="is_visible"
                        value="1"
                        @checked(old('is_visible', true))
                    >

                    Visible on the public site
                </label>
            </div>

            <br>

            <button type="submit">
                Save category
            </button>
        </form>
    </main>

</body>
</html>