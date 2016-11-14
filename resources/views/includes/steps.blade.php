@if((is_null($completedSteps) || (count($completedSteps) < 5)) && $loggedInUser->isAdmin())
    <div class="steps-wrapper">
        <div class="pull-left">
            <p>Please <a href="{{url('incompleteStep')}}">finish setting up your account</a> to publish your activities to the IATI registry.</p>
        </div>
        <ul>
            <li class= {{(in_array(1,(array)$completedSteps)) ? "checked" : "unchecked"}} >
                <a href="{{(in_array(1,(array)$completedSteps)) ? '#' : url('publishing-settings#1')}}">Registry Information</a>
            </li>
            <li class= {{(in_array(2,(array)$completedSteps)) ? "checked" : "unchecked"}} >
                <a href="{{(in_array(2,(array)$completedSteps)) ? '#' : url('publishing-settings#2')}}">Publishing Type for Activities</a>
            </li>
            <li class= {{(in_array(3,(array)$completedSteps)) ? "checked" : "unchecked"}} >
                <a href="{{(in_array(3,(array)$completedSteps)) ? '#' : url('publishing-settings#3')}}">Update the IATI Registry</a>
            </li>
            <li class= {{(in_array(4,(array)$completedSteps)) ? "checked" : "unchecked"}} >
                <a href="{{(in_array(4,(array)$completedSteps)) ? '#' : url('activity-elements-checklist#4')}}">Activity Elements Checklist</a>
            </li>
            <li class= {{(in_array(5,(array)$completedSteps)) ? "checked" : "unchecked"}} >
                <a href="{{(in_array(5,(array)$completedSteps)) ? '#' : url('default-values#5')}}">Default Values</a>
            </li>
        </ul>
        <div class="show-links pull-right">
            <span class="show-more">Show More</span>
            <span class="show-less">Show Less</span>
        </div>
    </div>
@elseif(count($completedSteps) < 5 && (!$loggedInUser->isAdmin()))
    <div class="steps-wrapper">
        <p>
            Your organisation's account has not been set up. Please contact your <strong>Administrator</strong> to finish setting up the account.
        </p>
    </div>
@endif