@extends('layouts.app')

@section('content')
<main class="container">
    <section>
        <div class="titlebar">
            <h1>Products</h1>
            <a href="{{route('product.create')}}" class="btn-link">Add Product</a>
        </div>
        @if ($message = Session::get('success'))
            <script type="text/javascript">
              const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
                })

                Toast.fire({
                icon: 'success',
                title: '{{ $message }}'
                })
            </script>
        @endif
        <div class="table">
            <div class="table-filter">
                <div>
                    <ul class="table-filter-list">
                        <li>
                            <p class="table-filter-link link-active">All</p>
                        </li>
                    </ul>
                </div>
            </div>
            <form method="GET" action="{{route('product.index')}}" accept-charset="UTF-8" role="search">
                    <div class="table-search">   
                        <div>
                            <button class="search-select">
                            Search Product
                            </button>
                            <span class="search-select-arrow">
                                <i class="fas fa-caret-down"></i>
                            </span>
                        </div>
                        <div class="relative">
                            <input class="search-input" type="text" name="search" placeholder="Search product..." name="search" value="{{request('search')}}">
                        </div>
                    </div>
            </form>
            <div class="table-product-head">
                <p>Image</p>
                <p>Name</p>
                <p>Category</p>
                <p>Inventory</p>
                <p>Actions</p>
            </div>
            <div class="table-product-body">
                @if (count($products)>0)
                @foreach ($products as $product)
                <img src="{{asset('image/' . $product->image)}}"/>
                <p> {{$product->name}}</p>
                <p>{{$product->category}}</p>
                <p>{{$product->quantity}}</p>
                <div style="display: flex">     
                    <a href="{{ route('product.edit', $product->id)}}" class="btn-link btn btn-success" style="padding-top: 4px; padding-bottom: 4px">
                        <i class="fas fa-pencil-alt" ></i> 
                    </a>
                    <form method="POST" action="{{route('product.destroy', $product->id)}}">
                      @method('delete')
                      @csrf
                            <button class="btn btn-danger" onclick="deleteConfirm(event)">
                                <i class="far fa-trash-alt"></i>
                            </button>
                    </form>
                    
                </div>
                @endforeach
                @else 
                <p>No Product Found</p>
                @endif
                
            </div>
            <div class="table-paginate">
                {{ $products->links('layouts.pagination') }}
                
            </div>
        </div>
    </section>
</main>
    <script>
        window.deleteConfirm = function (e){
            e.prevenDefault();
            var form = e.target.form;
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
               form.submit();
            }
            })
        }
        </script>    
@endsection


