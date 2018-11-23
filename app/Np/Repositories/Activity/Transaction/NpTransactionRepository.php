<?php namespace App\Np\Repositories\Activity\Transaction;

use App\Np\Contracts\NpActivityRepositoryInterface;
use App\Np\Contracts\NpTransactionRepositoryInterface;
use App\Np\Repositories\Activity\NpActivityRepository;
use App\Models\Activity\Transaction;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TransactionRepository
 * @package App\Np\Repositories\Transaction
 */
class NpTransactionRepository implements NpTransactionRepositoryInterface
{
    /**
     * @var Transaction
     */
    protected $transaction;
    /**
     * @var ActivityRepositoryInterface
     */
    private $activityRepository;

    /**
     * TransactionRepository constructor.
     *
     * @param Transaction                 $transaction
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(Transaction $transaction, NpActivityRepositoryInterface $activityRepository)
    {
        $this->transaction        = $transaction;
        $this->activityRepository = $activityRepository;
    }

    /**
     * Get all the Transactions of the current Transaction.
     *
     * @param $id
     * @return Collection
     */
    public function all($id)
    {
        // TODO: Implement all() method.
    }

    /**
     * Find an Transaction by its id.
     *
     * @param $id
     * @return Transaction
     */
    public function find($id)
    {
        return $this->transaction->findOrFail($id);
    }

    /**
     * Find Transactions by activity id
     *
     * @param $id
     * @return mixed
     */
    public function findByActivityId($id)
    {
        return $this->transaction->where('activity_id', $id)->get();
    }

    /**
     * Save the Transaction data into the database.
     *
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        $activity = $this->activityRepository->find(getVal($data, ['activity_id']));
        $this->activityRepository->resetWorkflow($activity)->save();

        return $this->transaction->create($data);
    }

    /**
     * Update current transactions
     *
     * @param array $data
     * @return mixed
     * @internal param $id
     */
    public function update(array $data)
    {
        $activity = $this->activityRepository->find(getVal($data, ['activity_id']));
        $this->activityRepository->resetWorkflow($activity)->save();

        $transaction = $this->transaction->findOrFail($data['id']);

        return $transaction->update($data);
    }

    /**
     * Deletes a transaction
     *
     * @param $activityId
     * @param $index
     * @return mixed
     * @throws \Exception
     */
    public function delete($activityId, $index)
    {
        $activity = $this->activityRepository->find($activityId);
        $this->activityRepository->resetWorkflow($activity)->save();

        $transaction = $activity->transactions()->where('id', '=', $index)->first();

        if ($transaction) {
            return $transaction->delete();
        } else {
            throw new \Exception("You don't have correct privilege to delete transaction");
        }
    }
}

