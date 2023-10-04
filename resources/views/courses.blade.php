@extends('master')
@section('content')
    <a href="/editcourse">
        <button type="button" class="btn btn-success mb-2">Thêm mới</button>
    </a>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">{{Helper::D2Ksub_str("Description Description Description Description Description", 12)}}  </th>
                <th scope="col">Image</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($course as $cou)
                <tr>
                    <th scope="row">{{ $cou->id }}</th>
                    <td>{{ $cou->name }}</td>
                    <td>{{ $cou->description }}</td>
                    <td><img src="{{asset("thumbs/200x50x2/upload/product/".$cou->image)}}" alt=""></td>
                    <td class="action">
                        <a href="/editcourse/{{ $cou->id }}" class="edit">
                            <button type="button" class="btn btn-outline-warning">Chỉnh sửa</button>
                        </a>
                        <a href="/delete/{{ $cou->id }}" class="delete">
                            <!-- bắt sự kiện -->
                            <button type="button" class="btn btn-outline-danger">Xóa</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <?php echo $course->render(); ?>
@endsection
@section('title', 'Khoa Hoc')
