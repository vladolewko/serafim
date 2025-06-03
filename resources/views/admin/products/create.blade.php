<div> @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label for="name">Назва:</label>
        <input type="text" id="name" name="name" required>
        <label for="description">Опис:</label>
        <input type="text" id="description" name="description" required>
        <label for="price">ціна:</label>
        <input type="text" id="price" name="price" required>
        <label for="books_quantity">Кількість книг:</label>
        <input type="number" id="books_quantity" name="books_quantity" required>
        <label for="weight">вага:</label>
        <input type="number" id="weight" name="weight" required>
        <label for="dimension">розмір:</label>
        <input type="text" id="dimension" name="dimension" required>
        <label for="content">Вміст:</label>
        <input type="text" id="content" name="content" required>
        <label for="for_whom">Для кого призначено:</label>
        <input type="text" id="for_whom" name="for_whom" required>
        <label for="appointment">призначення комплект:</label>
        <input type="text" id="appointment" name="appointment" required>
        <label for="image">Зображення:</label>
        <input type="file" id="image" name="product_image" required>

        <button type="submit">Create Product</button>
    </form>
</div>
