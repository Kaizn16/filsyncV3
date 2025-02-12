@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Users</h2>
        </header>

        <div class="content-box">
            <div class="table-container">
                <heder class="table-header">
                    <h2 class="title">
                        All Users
                    </h2>
                    <div class="table-header-actions">
                        <div class="search_wrap search_wrap_1">
                            <div class="search_box">
                                <input type="text" class="input" id="search" name="search_user" placeholder="Search....">
                                <div class="btn btn_common">
                                    <i class="search-icon material-icons">search</i>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="NewUser"><i class="material-icons icon">add</i>New User</button>
                    </div>
                </heder>
                <table class="table">
                    <thead>
                        <tr class="heading">
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th class="pagination">
                                <span class="previous" title="Previous"><i class="material-icons">keyboard_arrow_left</i></span>
                                <span class="next" title="Next"><i class="material-icons">keyboard_arrow_right</i></span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="tableData">
                        
                    </tbody>
                </table>
            </div>
           
        </div>

    </section>
@endsection
@section('script')
<script src="{{ asset('assets/js/users/admin.users.js') }}"></script>
@if (session('message'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        showToast("{{ session('type') }}", "{{ session('message') }}");
    });
</script>
@endif
@endsection