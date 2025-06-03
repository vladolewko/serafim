<div>
    <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
     <p>admin products</p>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


     <a href="{{ route('admin.logout') }}">Log Out</a>
     <a href="{{ route('admin.products.create') }}">Create New Product</a>

     <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Edit</th>
            
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td></td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}">Edit</a>
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
     </table>
</div>
