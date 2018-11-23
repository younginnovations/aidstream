<?php namespace App\Np\Contracts;

use App\Models\Activity\Transaction;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface TransactionRepositoryInterface
 * @package App\Np\Contracts
 */
interface NpTransactionRepositoryInterface
{
    /**
     * Get all the Transactions of the current Transaction.
     *
     * @param $id
     * @return Collection
     */
    public function all($id);

    /**
     * Find an Transaction by its id.
     *
     * @param $id
     * @return Transaction
     */
    public function find($id);

    /**
     * Save the Transaction data into the database.
     *
     * @param array $data
     * @return mixed
     */
    public function save(array $data);

    /**
     * Updates a transaction
     *
     * @param array $data
     * @return mixed
     * @internal param $id
     */
    public function update(array $data);

    /**
     * Finds transactions by activity id
     *
     * @param $id
     * @return mixed
     */
    public function findByActivityId($id);

    /**
     * Deletes a transaction
     *
     * @param $index
     * @return mixed
     */
    public function delete($id, $index);
}
