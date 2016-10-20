<ol>
    <a href="{{url('publishing-settings#1')}}">
        <li style="{{(array_key_exists(1,array_flip($completedSteps)))
                                ? 'border:1px solid black;background:green;border-radius:50%;display:inline' : 'border:1px solid black;background:red;border-radius:50%;display:inline'}} ">1
        </li>
    </a>
    <a href="{{url('publishing-settings#2')}}">
        <li style="{{(array_key_exists(2,array_flip($completedSteps)))
                                ? 'border:1px solid black;background:green;border-radius:50%;display:inline' : 'border:1px solid black;background:red;border-radius:50%;display:inline'}} ">2
        </li>
    </a>
    <a href="{{url('publishing-settings#3')}}">
        <li style="{{(array_key_exists(3,array_flip($completedSteps)))
                                ? 'border:1px solid black;background:green;border-radius:50%;display:inline' : 'border:1px solid black;background:red;border-radius:50%;display:inline'}} "
        >3
        </li>
    </a>
    <a href="{{url('activity-elements-checklist#4')}}">
        <li style="{{(array_key_exists(4,array_flip($completedSteps)))
                                ? 'border:1px solid black;background:green;border-radius:50%;display:inline' : 'border:1px solid black;background:red;border-radius:50%;display:inline'}} ">4
        </li>
    </a>
    <a href="{{url('default-values#5')}}">
        <li style="{{(array_key_exists(5,array_flip($completedSteps)))
                                ? 'border:1px solid black;background:green;border-radius:50%;display:inline' : 'border:1px solid black;background:red;border-radius:50%;display:inline'}} ">5
        </li>
    </a>
</ol>