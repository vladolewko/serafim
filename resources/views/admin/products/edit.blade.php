<div>
    <p>сторінка товару {{ $product->name }}</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.products.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <label for="name">Назва:</label>
        <input type="text" id="name" name="name" value="{{ $product->name }}" required>
        @if($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
        <label for="description">Опис:</label>
        <input type="text" id="description" name="description" value="{{ $product->description }}" required>
        @if($errors->has('description'))
            <span class="text-danger">{{ $errors->first('description') }}</span>
        @endif
        <label for="price">ціна:</label>
        <input type="text" id="price" name="price" value="{{ $product->price }}" required>
        @if($errors->has('price'))
            <span class="text-danger">{{ $errors->first('price') }}</span>
        @endif
        <label for="books_quantity">Кількість книг:</label>
        <input type="number" id="books_quantity" name="books_quantity" value="{{ $product->books_quantity }}" required>
        @if($errors->has('books_quantity'))
            <span class="text-danger">{{ $errors->first('books_quantity') }}</span>
        @endif
        <label for="weight">вага:</label>
        <input type="number" id="weight" name="weight" value="{{ $product->weight }}" required>
        @if($errors->has('weight'))
            <span class="text-danger">{{ $errors->first('weight') }}</span>
        @endif
        <label for="dimension">розмір:</label>
        <input type="text" id="dimension" name="dimension" value="{{ $product->dimension }}" required>
        @if($errors->has('dimension'))
            <span class="text-danger">{{ $errors->first('dimension') }}</span>
        @endif
        <label for="content">Вміст:</label>
        <textarea id="content" name="content"  required>{{ $product->content }}</textarea>
        @if($errors->has('content'))
            <span class="text-danger">{{ $errors->first('content') }}</span>
        @endif
        <label for="for_whom">Для кого призначено:</label>
        <textarea id="for_whom" name="for_whom" required>{{ $product->for_whom }}</textarea>
        @if($errors->has('for_whom'))
            <span class="text-danger">{{ $errors->first('for_whom') }}</span>
        @endif
        <label for="appointment">призначення комплект:</label>
        <input type="text" id="appointment" name="appointment" value="{{ $product->appointment }}" required>
        @if($errors->has('appointment'))
            <span class="text-danger">{{ $errors->first('appointment') }}</span>
        @endif
        <label for="image">Зображення:</label>
        <input type="file" id="image" name="product_image">
        @if($errors->has('product_image'))
            <span class="text-danger">{{ $errors->first('product_image') }}</span>
            @endif

@if($product->getMedia('product_images')->isNotEmpty())

                            <img
                                src="{{ $product->getFirstMediaUrl('product_images') }}"
                                alt="{{ $product->name }}"
                            >

                @endif
        <button type="submit">Update Product</button>
    </form>
    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
        @csrf
        @method('DELETE')
        <button type="submit">Delete Product</button>
    </form>
</div>
