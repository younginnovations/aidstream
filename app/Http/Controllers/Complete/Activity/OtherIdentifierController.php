<?php namespace app\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\OtherIdentifierManager;
use App\Services\FormCreator\Activity\OtherIdentifierForm;
use App\Services\RequestManager\Activity\OtherIdentifierRequestManager;
use Illuminate\Http\Request;

class OtherIdentifierController extends Controller
{
    /**
     * @var OtherIdentifierManager
     */
    protected $otherIdentifierManager;
    /**
     * @var OtherIdentifierForm
     */
    protected $otherIdentifierForm;

    /**
     * @param OtherIdentifierManager $otherIdentifierManager
     * @param OtherIdentifierForm    $otherIdentifierForm
     */
    function __construct(OtherIdentifierManager $otherIdentifierManager, OtherIdentifierForm $otherIdentifierForm)
    {
        $this->middleware('auth');
        $this->otherIdentifierManager = $otherIdentifierManager;
        $this->otherIdentifierForm    = $otherIdentifierForm;
    }

    public function index($id)
    {
        $otherIdentifier = $this->otherIdentifierManager->getOtherIdentifierData($id);
        $form            = $this->otherIdentifierForm->editForm($otherIdentifier, $id);

        return view(
            'Activity.otherIdentifier.otherIdentifier',
            compact('form', 'otherIdentifier', 'id')
        );
    }

    /**
     * write brief description
     * @param OtherIdentifierRequestManager $otherIdentifierRequestManager
     * @param Request                       $request
     * @param                               $id
     */
    public function update(OtherIdentifierRequestManager $otherIdentifierRequestManager, Request $request, $id)
    {
        $input        = $request->all();
        $activityData = $this->otherIdentifierManager->getActivityData($id);

        if ($this->otherIdentifierManager->update($input, $activityData)) {
            return redirect()->to(sprintf('/activity/%s', $id))->withMessage(
                'Activity Other Identifier Updated !'
            );
        }

        return redirect()->back();
    }
}
