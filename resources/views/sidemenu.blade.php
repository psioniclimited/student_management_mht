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
            <li {!! Request::is('*payment_student*') ? ' class="active"' : null !!}><a href="{{url('payment_student')}}"><i class="fa fa-circle-o"></i> Payment</a></li>
            <li {!! Request::is('*create_school*') ? ' class="active"' : null !!}><a href="{{url('create_school')}}"><i class="fa fa-circle-o"></i> New School</a></li>
            <li {!! Request::is('*all_batches*') ? ' class="active"' : null !!}><a href="{{url('all_batches')}}"><i class="fa fa-circle-o"></i> All Batches</a></li>
            <li {!! Request::is('*create_batch*') ? ' class="active"' : null !!}><a href="{{url('create_batch')}}"><i class="fa fa-circle-o"></i> New Batch</a></li>
            <li {!! Request::is('*all_grades*') ? ' class="active"' : null !!}><a href="{{url('all_grades')}}"><i class="fa fa-circle-o"></i> All Grades</a></li>
            <li {!! Request::is('*create_grade*') ? ' class="active"' : null !!}><a href="{{url('create_grade')}}"><i class="fa fa-circle-o"></i> New Grade</a></li>
            <li {!! Request::is('*create_batch_type*') ? ' class="active"' : null !!}><a href="{{url('create_batch_type')}}"><i class="fa fa-circle-o"></i> New Batch Type</a></li>
        </ul>
    </li>
    <li {!! Request::is('*teacher*') ? ' class="active treeview"' : ' class="treeview"' !!} class="treeview">
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Teacher</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {!! Request::is('*all_teachers*') ? ' class="active"' : null !!}><a href="{{url('all_teachers')}}"><i class="fa fa-circle-o"></i> All Teachers</a></li>
            <li {!! Request::is('*create_teacher*') ? ' class="active"' : null !!}><a href="{{url('create_teacher')}}"><i class="fa fa-circle-o"></i> New Teacher</a></li>
        </ul>
    </li>
    <li {!! Request::is('*batch*') ? ' class="active treeview"' : ' class="treeview"' !!} class="treeview">
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Batch</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {!! Request::is('*all_batches*') ? ' class="active"' : null !!}><a href="{{url('all_teachers')}}"><i class="fa fa-circle-o"></i> Physics</a></li>
            <li {!! Request::is('*create_teacher*') ? ' class="active"' : null !!}><a href="{{url('create_teacher')}}"><i class="fa fa-circle-o"></i> Chemistry</a></li>
            <li {!! Request::is('*create_teacher*') ? ' class="active"' : null !!}><a href="{{url('create_teacher')}}"><i class="fa fa-circle-o"></i> Mathematics</a></li>
        </ul>
    </li>
    <li {!! Request::is('*reporting*') ? ' class="active treeview"' : ' class="treeview"' !!} class="treeview">
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Reporting</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {!! Request::is('*daily_reporting*') ? ' class="active"' : null !!}><a href="{{url('daily_reporting')}}"><i class="fa fa-circle-o"></i> Daily Reporting</a></li>
            <li {!! Request::is('*due_reporting*') ? ' class="active"' : null !!}><a href="{{url('due_reporting')}}"><i class="fa fa-circle-o"></i> Due Reporting</a></li>
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
