@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Course/Subject Management</h2>
        </header>

        <div class="content-box">
           <div class="list-container">
                <header class="header">
                    <h2 class="title">
                        Departments
                    </h2>
                    <div class="table-header-actions">
                        <div class="search_wrap search_wrap_1">
                            <div class="search_box">
                                <input type="text" class="input" id="search" name="search_department" placeholder="Search....">
                                <div class="btn btn_common">
                                    <i class="search-icon material-icons">search</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <ul class="departments-list">
                    
                </ul>
           </div>
        </div>

    </section>
@endsection
@section('script')
<script src="{{ asset('assets/js/academics/academics.js') }}"></script>
@if (session('message'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        showToast("{{ session('type') }}", "{{ session('message') }}");
    });
</script>
@endif
@endsection