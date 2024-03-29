<?php

namespace GroupOrder\Model\Base;

use \Exception;
use \PDO;
use GroupOrder\Model\GroupOrderSubOrder as ChildGroupOrderSubOrder;
use GroupOrder\Model\GroupOrderSubOrderQuery as ChildGroupOrderSubOrderQuery;
use GroupOrder\Model\Map\GroupOrderSubOrderTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'group_order_sub_order' table.
 *
 *
 *
 * @method     ChildGroupOrderSubOrderQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildGroupOrderSubOrderQuery orderBySubCustomerId($order = Criteria::ASC) Order by the sub_customer_id column
 * @method     ChildGroupOrderSubOrderQuery orderByGroupOrderId($order = Criteria::ASC) Order by the group_order_id column
 *
 * @method     ChildGroupOrderSubOrderQuery groupById() Group by the id column
 * @method     ChildGroupOrderSubOrderQuery groupBySubCustomerId() Group by the sub_customer_id column
 * @method     ChildGroupOrderSubOrderQuery groupByGroupOrderId() Group by the group_order_id column
 *
 * @method     ChildGroupOrderSubOrderQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGroupOrderSubOrderQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGroupOrderSubOrderQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGroupOrderSubOrderQuery leftJoinGroupOrderSubCustomer($relationAlias = null) Adds a LEFT JOIN clause to the query using the GroupOrderSubCustomer relation
 * @method     ChildGroupOrderSubOrderQuery rightJoinGroupOrderSubCustomer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GroupOrderSubCustomer relation
 * @method     ChildGroupOrderSubOrderQuery innerJoinGroupOrderSubCustomer($relationAlias = null) Adds a INNER JOIN clause to the query using the GroupOrderSubCustomer relation
 *
 * @method     ChildGroupOrderSubOrderQuery leftJoinGroupOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the GroupOrder relation
 * @method     ChildGroupOrderSubOrderQuery rightJoinGroupOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GroupOrder relation
 * @method     ChildGroupOrderSubOrderQuery innerJoinGroupOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the GroupOrder relation
 *
 * @method     ChildGroupOrderSubOrderQuery leftJoinGroupOrderProduct($relationAlias = null) Adds a LEFT JOIN clause to the query using the GroupOrderProduct relation
 * @method     ChildGroupOrderSubOrderQuery rightJoinGroupOrderProduct($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GroupOrderProduct relation
 * @method     ChildGroupOrderSubOrderQuery innerJoinGroupOrderProduct($relationAlias = null) Adds a INNER JOIN clause to the query using the GroupOrderProduct relation
 *
 * @method     ChildGroupOrderSubOrder findOne(ConnectionInterface $con = null) Return the first ChildGroupOrderSubOrder matching the query
 * @method     ChildGroupOrderSubOrder findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGroupOrderSubOrder matching the query, or a new ChildGroupOrderSubOrder object populated from the query conditions when no match is found
 *
 * @method     ChildGroupOrderSubOrder findOneById(int $id) Return the first ChildGroupOrderSubOrder filtered by the id column
 * @method     ChildGroupOrderSubOrder findOneBySubCustomerId(int $sub_customer_id) Return the first ChildGroupOrderSubOrder filtered by the sub_customer_id column
 * @method     ChildGroupOrderSubOrder findOneByGroupOrderId(int $group_order_id) Return the first ChildGroupOrderSubOrder filtered by the group_order_id column
 *
 * @method     array findById(int $id) Return ChildGroupOrderSubOrder objects filtered by the id column
 * @method     array findBySubCustomerId(int $sub_customer_id) Return ChildGroupOrderSubOrder objects filtered by the sub_customer_id column
 * @method     array findByGroupOrderId(int $group_order_id) Return ChildGroupOrderSubOrder objects filtered by the group_order_id column
 *
 */
abstract class GroupOrderSubOrderQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \GroupOrder\Model\Base\GroupOrderSubOrderQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\GroupOrder\\Model\\GroupOrderSubOrder', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGroupOrderSubOrderQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGroupOrderSubOrderQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \GroupOrder\Model\GroupOrderSubOrderQuery) {
            return $criteria;
        }
        $query = new \GroupOrder\Model\GroupOrderSubOrderQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildGroupOrderSubOrder|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = GroupOrderSubOrderTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GroupOrderSubOrderTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildGroupOrderSubOrder A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, SUB_CUSTOMER_ID, GROUP_ORDER_ID FROM group_order_sub_order WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildGroupOrderSubOrder();
            $obj->hydrate($row);
            GroupOrderSubOrderTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildGroupOrderSubOrder|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GroupOrderSubOrderTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GroupOrderSubOrderTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GroupOrderSubOrderTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GroupOrderSubOrderTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupOrderSubOrderTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the sub_customer_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySubCustomerId(1234); // WHERE sub_customer_id = 1234
     * $query->filterBySubCustomerId(array(12, 34)); // WHERE sub_customer_id IN (12, 34)
     * $query->filterBySubCustomerId(array('min' => 12)); // WHERE sub_customer_id > 12
     * </code>
     *
     * @see       filterByGroupOrderSubCustomer()
     *
     * @param     mixed $subCustomerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function filterBySubCustomerId($subCustomerId = null, $comparison = null)
    {
        if (is_array($subCustomerId)) {
            $useMinMax = false;
            if (isset($subCustomerId['min'])) {
                $this->addUsingAlias(GroupOrderSubOrderTableMap::SUB_CUSTOMER_ID, $subCustomerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subCustomerId['max'])) {
                $this->addUsingAlias(GroupOrderSubOrderTableMap::SUB_CUSTOMER_ID, $subCustomerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupOrderSubOrderTableMap::SUB_CUSTOMER_ID, $subCustomerId, $comparison);
    }

    /**
     * Filter the query on the group_order_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupOrderId(1234); // WHERE group_order_id = 1234
     * $query->filterByGroupOrderId(array(12, 34)); // WHERE group_order_id IN (12, 34)
     * $query->filterByGroupOrderId(array('min' => 12)); // WHERE group_order_id > 12
     * </code>
     *
     * @see       filterByGroupOrder()
     *
     * @param     mixed $groupOrderId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function filterByGroupOrderId($groupOrderId = null, $comparison = null)
    {
        if (is_array($groupOrderId)) {
            $useMinMax = false;
            if (isset($groupOrderId['min'])) {
                $this->addUsingAlias(GroupOrderSubOrderTableMap::GROUP_ORDER_ID, $groupOrderId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupOrderId['max'])) {
                $this->addUsingAlias(GroupOrderSubOrderTableMap::GROUP_ORDER_ID, $groupOrderId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupOrderSubOrderTableMap::GROUP_ORDER_ID, $groupOrderId, $comparison);
    }

    /**
     * Filter the query by a related \GroupOrder\Model\GroupOrderSubCustomer object
     *
     * @param \GroupOrder\Model\GroupOrderSubCustomer|ObjectCollection $groupOrderSubCustomer The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function filterByGroupOrderSubCustomer($groupOrderSubCustomer, $comparison = null)
    {
        if ($groupOrderSubCustomer instanceof \GroupOrder\Model\GroupOrderSubCustomer) {
            return $this
                ->addUsingAlias(GroupOrderSubOrderTableMap::SUB_CUSTOMER_ID, $groupOrderSubCustomer->getId(), $comparison);
        } elseif ($groupOrderSubCustomer instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GroupOrderSubOrderTableMap::SUB_CUSTOMER_ID, $groupOrderSubCustomer->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGroupOrderSubCustomer() only accepts arguments of type \GroupOrder\Model\GroupOrderSubCustomer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GroupOrderSubCustomer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function joinGroupOrderSubCustomer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GroupOrderSubCustomer');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'GroupOrderSubCustomer');
        }

        return $this;
    }

    /**
     * Use the GroupOrderSubCustomer relation GroupOrderSubCustomer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GroupOrder\Model\GroupOrderSubCustomerQuery A secondary query class using the current class as primary query
     */
    public function useGroupOrderSubCustomerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGroupOrderSubCustomer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GroupOrderSubCustomer', '\GroupOrder\Model\GroupOrderSubCustomerQuery');
    }

    /**
     * Filter the query by a related \GroupOrder\Model\GroupOrder object
     *
     * @param \GroupOrder\Model\GroupOrder|ObjectCollection $groupOrder The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function filterByGroupOrder($groupOrder, $comparison = null)
    {
        if ($groupOrder instanceof \GroupOrder\Model\GroupOrder) {
            return $this
                ->addUsingAlias(GroupOrderSubOrderTableMap::GROUP_ORDER_ID, $groupOrder->getId(), $comparison);
        } elseif ($groupOrder instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GroupOrderSubOrderTableMap::GROUP_ORDER_ID, $groupOrder->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGroupOrder() only accepts arguments of type \GroupOrder\Model\GroupOrder or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GroupOrder relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function joinGroupOrder($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GroupOrder');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'GroupOrder');
        }

        return $this;
    }

    /**
     * Use the GroupOrder relation GroupOrder object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GroupOrder\Model\GroupOrderQuery A secondary query class using the current class as primary query
     */
    public function useGroupOrderQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGroupOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GroupOrder', '\GroupOrder\Model\GroupOrderQuery');
    }

    /**
     * Filter the query by a related \GroupOrder\Model\GroupOrderProduct object
     *
     * @param \GroupOrder\Model\GroupOrderProduct|ObjectCollection $groupOrderProduct  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function filterByGroupOrderProduct($groupOrderProduct, $comparison = null)
    {
        if ($groupOrderProduct instanceof \GroupOrder\Model\GroupOrderProduct) {
            return $this
                ->addUsingAlias(GroupOrderSubOrderTableMap::ID, $groupOrderProduct->getSubOrderId(), $comparison);
        } elseif ($groupOrderProduct instanceof ObjectCollection) {
            return $this
                ->useGroupOrderProductQuery()
                ->filterByPrimaryKeys($groupOrderProduct->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByGroupOrderProduct() only accepts arguments of type \GroupOrder\Model\GroupOrderProduct or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GroupOrderProduct relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function joinGroupOrderProduct($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GroupOrderProduct');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'GroupOrderProduct');
        }

        return $this;
    }

    /**
     * Use the GroupOrderProduct relation GroupOrderProduct object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GroupOrder\Model\GroupOrderProductQuery A secondary query class using the current class as primary query
     */
    public function useGroupOrderProductQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGroupOrderProduct($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GroupOrderProduct', '\GroupOrder\Model\GroupOrderProductQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGroupOrderSubOrder $groupOrderSubOrder Object to remove from the list of results
     *
     * @return ChildGroupOrderSubOrderQuery The current query, for fluid interface
     */
    public function prune($groupOrderSubOrder = null)
    {
        if ($groupOrderSubOrder) {
            $this->addUsingAlias(GroupOrderSubOrderTableMap::ID, $groupOrderSubOrder->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the group_order_sub_order table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupOrderSubOrderTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            GroupOrderSubOrderTableMap::clearInstancePool();
            GroupOrderSubOrderTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildGroupOrderSubOrder or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildGroupOrderSubOrder object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupOrderSubOrderTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GroupOrderSubOrderTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        GroupOrderSubOrderTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            GroupOrderSubOrderTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // GroupOrderSubOrderQuery
