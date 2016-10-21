<ol>
    <li class="{{(array_key_exists(1,array_flip($completedSteps)))? 'filled' : 'skipped'}} ">
        <a href="{{url('publishing-settings#1')}}">1</a>
    </li>
    <li class="{{(array_key_exists(2,array_flip($completedSteps))) ? 'filled' : 'skipped'}} ">
        <a href="{{url('publishing-settings#2')}}">2</a>
    </li>
    <li class="{{(array_key_exists(3,array_flip($completedSteps))) ? 'filled' : 'skipped'}} "
    >
        <a href="{{url('publishing-settings#3')}}">3
        </a>
    </li>
    <li class="{{(array_key_exists(4,array_flip($completedSteps))) ? 'filled' : 'skipped'}} ">
        <a href="{{url('activity-elements-checklist#4')}}">4</a>
    </li>
    <li class="{{(array_key_exists(5,array_flip($completedSteps))) ? 'filled' : 'skipped'}} ">
        <a href="{{url('default-values#5')}}">5</a>
    </li>
</ol>