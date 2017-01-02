<?php namespace App\Http\Controllers\Complete\Traits;

/**
 * Trait WorkflowResponses
 * @package App\Http\Controllers\Complete\Traits
 */
trait WorkflowResponses
{
    /**
     * Respond to the Activity Workflow for different stages in the workflow.
     * @param      $updated
     * @param      $activityWorkflow
     * @return array
     */
    protected function respondTo($updated, $activityWorkflow)
    {
        $statusLabel   = ['Completed', 'Verified', 'Published'];
        $currentStatus = $activityWorkflow - 1;

        if ($updated) {
            return ['type' => 'success', 'code' => ['activity_statuses', ['name' => $statusLabel[$currentStatus]]]];
        }

        return ['type' => 'danger', 'code' => ['activity_statuses_failed', ['name' => $statusLabel[$currentStatus]]]];
    }
}
