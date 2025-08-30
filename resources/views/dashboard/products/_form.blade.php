<div class="form-group">
    <x-form.input label="Product Name" class="form-control-lg" role="input" name="name" :value="$product->name" />
</div>

<div class="form-group">
    <label for="">Category</label>
    <select name="category_id" class="form-control form-select">
        <option value="">Primary Category</option>
        @foreach (App\Models\Category::all() as $category)
        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
            {{ $category->name }}
        </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="">Description</label>
    <x-form.textarea name="description" :value="$product->description" />
</div>

<div class="form-group">
    <label for="image">Choose Image</label>
    <x-form.input type="file" name="image" accept="image/*" onchange="previewImage(event)" />
    <img id="preview-image" src="{{ asset('storage/' . $product->image) }}" alt="Selected Image"
        style="max-height: 150px;" class="mt-2 rounded" @if (!$product->image) style="display:none" @endif>
</div>

<div class="form-group">
    <x-form.input label="Price" name="price" :value="$product->price" />
</div>

<div class="form-group">
    <x-form.input label="Tags" name="tags" :value="$tags" />
</div>

<div class="form-group">
    <label for="">Status</label>
    <div>
        <x-form.radio name="status" :checked="$product->status"
            :options="['active' => 'Active', 'draft' => 'Draft', 'archived' => 'Archived']" />
    </div>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">Save</button>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    // Initialize Tagify
        const inputElm = document.querySelector('[name=tags]');
        const tagify = new Tagify(inputElm);

        // Image preview function
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('preview-image');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
</script>
@endpush
