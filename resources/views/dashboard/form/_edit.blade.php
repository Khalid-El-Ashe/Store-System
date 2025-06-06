<div class="card card-success">

    {{-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif --}}

    <div class="card-header">
        <h3 class="card-title">Edit Category</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <x-form.input label="Category Name" type="text" name="name" value="{{$category->name}}"
                placeholder="category name" />
            {{-- <input type="text" @class(['form-control', 'is-invalid'=> $errors->has('name')])
            @if (old('name')) value="{{ old('name') }}" @else value="{{ old('name', $category->name ?? null) }}" @endif
            name="name" id="name" placeholder="category name">
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror --}}
        </div>

        <div class="form-group">
            <x-form.textarea label="Categoy Description" type="text" name="description"
                value="{{$category->description}}" />
            {{-- <label for="">Categoy Description</label>
            <textarea class="form-control form-control-lg" type="text" name="description" id="description"
                placeholder="category desciption....">{{$category->description}}</textarea> --}}
        </div>
        <div class="form-group">
            <label>Select</label>

            <select class="form-control" name="parent_id">
                <option value="">Primary Category</option>
                @foreach ($parents as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id ?? null) ==
                    $parent->id)>
                    {{ $parent->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="">Choose Image</label>
            <div class="custom-file">
                <label class="custom-file-label" for="image">Choose image</label>
                <input type="file" class="custom-file-input" name="image" id="image" multiple accept="image/*">
                {{-- لاحظ فوق اني انا فقط معطي المستخدم يرفع بس صور accept="image*/" --}}
            </div>
            <img id="preview-image" src="#" alt="Selected Image" style="display: none; max-height: 150px;"
                class="mt-2 rounded">
        </div>
        <div class="form-group clearfix">
            <x-form.radio name="status" checked="{{$category->status}}"
                :options="['active' => 'Active', 'archived' => 'Archived']" />
            {{-- <div class="icheck-primary d-inline">
                <input type="radio" id="status_active" name="status" value="active" @checked(old('status',
                    $category->status ??
                '') == 'active')>
                <label for="status_active">
                    Active
                </label>
            </div> --}}
            {{-- <div class="icheck-primary d-inline">
                <input type="radio" id="status_archived" name="status" value="archived" @checked(old('status',
                    $category->status
                ?? '') == 'archived')>
                <label for="status_archived">
                    Archived
                </label>
            </div> --}}
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>