<ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>
    <li class="active">
        <a href="/allusers">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>            
        </a>        
    </li>
    <li {!! Request::is('*users*') ? ' class="active treeview"' : ' class="treeview"' !!} class="treeview">
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Users</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
        
            <li {!! Request::is('*allusers*') ? ' class="active"' : null !!}><a href="{{url('allusers')}}"><i class="fa fa-circle-o"></i> All User</a></li>
         
            <li {!! Request::is('*create_users*') ? ' class="active"' : null !!}><a href="{{url('create_users')}}"><i class="fa fa-circle-o"></i> New User</a></li>
        </ul>
    </li>
    <li {!! Request::is('*student*') ? ' class="active treeview"' : ' class="treeview"' !!} class="treeview">
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Students</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
        
            <li {!! Request::is('*all_students*') ? ' class="active"' : null !!}><a href="{{url('all_students')}}"><i class="fa fa-circle-o"></i> All Students</a></li>
        
            <li {!! Request::is('*create_student*') ? ' class="active"' : null !!}><a href="{{url('create_student')}}"><i class="fa fa-circle-o"></i> New Student</a></li>
            <li {!! Request::is('*create_school*') ? ' class="active"' : null !!}><a href="{{url('create_school')}}"><i class="fa fa-circle-o"></i> New School</a></li>
            <li {!! Request::is('*create_batch*') ? ' class="active"' : null !!}><a href="{{url('create_batch')}}"><i class="fa fa-circle-o"></i> New Batch</a></li>
            <li {!! Request::is('*create_batch_type*') ? ' class="active"' : null !!}><a href="{{url('create_batch_type')}}"><i class="fa fa-circle-o"></i> New Batch Type</a></li>
        </ul>
    </li>
    <li {!! Request::is('*roles*') || Request::is('*permissions*') ? ' class="active treeview"' : ' class="treeview"' !!} class="treeview">
        <a href="#">
            <i class="fa fa-gears"></i>
            <span>Settings</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            
                <li {!! Request::is('*roles*') ? ' class="active"' : null !!}><a href="{{url('roles')}}"><i class="fa fa-circle-o"></i> Roles</a></li>
            
            
                <li {!! Request::is('*permissions*') ? ' class="active"' : null !!}><a href="{{url('permissions')}}"><i class="fa fa-circle-o"></i> Permission</a></li>
                             
        </ul>
    </li>
</ul>
