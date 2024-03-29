<?php

namespace GroupOrder\Model\Base;

use \Exception;
use \PDO;
use GroupOrder\Model\GroupOrder as ChildGroupOrder;
use GroupOrder\Model\GroupOrderQuery as ChildGroupOrderQuery;
use GroupOrder\Model\Map\GroupOrderTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Thelia\Model\Order;

/**
 * Base class that represents a query for the 'group_order' table.
 *
 *
 *
 * @method     ChildGroupOrderQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildGroupOrderQuery orderByMainCustomerId($order = Criteria::ASC) Order by the main_customer_id column
 * @method     ChildGroupOrderQuery orderByOrderId($order = Criteria::ASC) Order by the order_id column
 *
 * @method     ChildGroupOrderQuery groupById() Group by the id column
 * @method     ChildGroupOrderQuery groupByMainCustomerId() Group by the main_customer_id column
 * @method     ChildGroupOrderQuery groupByOrderId() Group by the order_id column
 *
 * @method     ChildGroupOrderQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGroupOrderQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGroupOrderQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGroupOrderQuery leftJoinGroupOrderMainCustomer($relationAlias = null) Adds a LEFT JOIN clause to the query using the GroupOrderMainCustomer relation
 * @method     ChildGroupOrderQuery rightJoinGroupOrderMainCustomer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GroupOrderMainCustomer relation
 * @method     ChildGroupOrderQuery innerJoinGroupOrderMainCustomer($relationAlias = null) Adds a INNER JOIN clause to the query using the GroupOrderMainCustomer relation
 *
 * @method     ChildGroupOrderQuery leftJoinOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the Order relation
 * @method     ChildGroupOrderQuery rightJoinOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Order relation
 * @method     ChildGroupOrderQuery innerJoinOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the Order relation
 *
 * @method     ChildGroupOrderQuery leftJoinGroupOrderSubOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the GroupOrderSubOrder relation
 * @method     ChildGroupOrderQuery rightJoinGroupOrderSubOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the GroupOrderSubOrder relation
 * @method     ChildGroupOrderQuery innerJoinGroupOrderSubOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the GroupOrderSubOrder relation
 *
 * @method     ChildGroupOrder findOne(ConnectionInterface $con = null) Return the first ChildGroupOrder matching the query
 * @method     ChildGroupOrder findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGroupOrder matching the query, or a new ChildGroupOrder object populated from the query conditions when no match is found
 *
 * @method     ChildGroupOrder findOneById(int $id) Return the first ChildGroupOrder filtered by the id column
 * @method     ChildGroupOrder findOneByMainCustomerId(int $main_customer_id) Return the first ChildGroupOrder filtered by the main_customer_id column
 * @method     ChildGroupOrder findOneByOrderId(int $order_id) Return the first ChildGroupOrder filtered by the order_id column
 *
 * @method     array findById(int $id) Return ChildGroupOrder objects filtered by the id column
 * @method     array findByMainCustomerId(int $main_customer_id) Return ChildGroupOrder objects filtered by the main_customer_id column
 * @method     array findByOrderId(int $order_id) Return ChildGroupOrder objects filtered by the order_id column
 *
 */
abstract class GroupOrderQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \GroupOrder\Model\Base\GroupOrderQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\GroupOrder\\Model\\GroupOrder', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGroupOrderQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGroupOrderQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \GroupOrder\Model\GroupOrderQuery) {
            return $criteria;
        }
        $query = new \GroupOrder\Model\GroupOrderQuery();
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
     * @return ChildGroupOrder|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = GroupOrderTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GroupOrderTableMap::DATABASE_NAME);
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
     * @return   ChildGroupOrder A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, MAIN_CUSTOMER_ID, ORDER_ID FROM group_order WHERE ID = :p0';
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
            $obj = new ChildGroupOrder();
            $obj->hydrate($row);
            GroupOrderTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildGroupOrder|array|mixed the result, formatted by the current formatter
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
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GroupOrderTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GroupOrderTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GroupOrderTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GroupOrderTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupOrderTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the main_customer_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMainCustomerId(1234); // WHERE main_customer_id = 1234
     * $query->filterByMainCustomerId(array(12, 34)); // WHERE main_customer_id IN (12, 34)
     * $query->filterByMainCustomerId(array('min' => 12)); // WHERE main_customer_id > 12
     * </code>
     *
     * @see       filterByGroupOrderMainCustomer()
     *
     * @param     mixed $mainCustomerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function filterByMainCustomerId($mainCustomerId = null, $comparison = null)
    {
        if (is_array($mainCustomerId)) {
            $useMinMax = false;
            if (isset($mainCustomerId['min'])) {
                $this->addUsingAlias(GroupOrderTableMap::MAIN_CUSTOMER_ID, $mainCustomerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mainCustomerId['max'])) {
                $this->addUsingAlias(GroupOrderTableMap::MAIN_CUSTOMER_ID, $mainCustomerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupOrderTableMap::MAIN_CUSTOMER_ID, $mainCustomerId, $comparison);
    }

    /**
     * Filter the query on the order_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderId(1234); // WHERE order_id = 1234
     * $query->filterByOrderId(array(12, 34)); // WHERE order_id IN (12, 34)
     * $query->filterByOrderId(array('min' => 12)); // WHERE order_id > 12
     * </code>
     *
     * @see       filterByOrder()
     *
     * @param     mixed $orderId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function filterByOrderId($orderId = null, $comparison = null)
    {
        if (is_array($orderId)) {
            $useMinMax = false;
            if (isset($orderId['min'])) {
                $this->addUsingAlias(GroupOrderTableMap::ORDER_ID, $orderId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderId['max'])) {
                $this->addUsingAlias(GroupOrderTableMap::ORDER_ID, $orderId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GroupOrderTableMap::ORDER_ID, $orderId, $comparison);
    }

    /**
     * Filter the query by a related \GroupOrder\Model\GroupOrderMainCustomer object
     *
     * @param \GroupOrder\Model\GroupOrderMainCustomer|ObjectCollection $groupOrderMainCustomer The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function filterByGroupOrderMainCustomer($groupOrderMainCustomer, $comparison = null)
    {
        if ($groupOrderMainCustomer instanceof \GroupOrder\Model\GroupOrderMainCustomer) {
            return $this
                ->addUsingAlias(GroupOrderTableMap::MAIN_CUSTOMER_ID, $groupOrderMainCustomer->getId(), $comparison);
        } elseif ($groupOrderMainCustomer instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GroupOrderTableMap::MAIN_CUSTOMER_ID, $groupOrderMainCustomer->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGroupOrderMainCustomer() only accepts arguments of type \GroupOrder\Model\GroupOrderMainCustomer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GroupOrderMainCustomer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function joinGroupOrderMainCustomer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GroupOrderMainCustomer');

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
            $this->addJoinObject($join, 'GroupOrderMainCustomer');
        }

        return $this;
    }

    /**
     * Use the GroupOrderMainCustomer relation GroupOrderMainCustomer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GroupOrder\Model\GroupOrderMainCustomerQuery A secondary query class using the current class as primary query
     */
    public function useGroupOrderMainCustomerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGroupOrderMainCustomer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GroupOrderMainCustomer', '\GroupOrder\Model\GroupOrderMainCustomerQuery');
    }

    /**
     * Filter the query by a related \Thelia\Model\Order object
     *
     * @param \Thelia\Model\Order|ObjectCollection $order The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function filterByOrder($order, $comparison = null)
    {
        if ($order instanceof \Thelia\Model\Order) {
            return $this
                ->addUsingAlias(GroupOrderTableMap::ORDER_ID, $order->getId(), $comparison);
        } elseif ($order instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(GroupOrderTableMap::ORDER_ID, $order->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByOrder() only accepts arguments of type \Thelia\Model\Order or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Order relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function joinOrder($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Order');

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
            $this->addJoinObject($join, 'Order');
        }

        return $this;
    }

    /**
     * Use the Order relation Order object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\OrderQuery A secondary query class using the current class as primary query
     */
    public function useOrderQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Order', '\Thelia\Model\OrderQuery');
    }

    /**
     * Filter the query by a related \GroupOrder\Model\GroupOrderSubOrder object
     *
     * @param \GroupOrder\Model\GroupOrderSubOrder|ObjectCollection $groupOrderSubOrder  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function filterByGroupOrderSubOrder($groupOrderSubOrder, $comparison = null)
    {
        if ($groupOrderSubOrder instanceof \GroupOrder\Model\GroupOrderSubOrder) {
            return $this
                ->addUsingAlias(GroupOrderTableMap::ID, $groupOrderSubOrder->getGroupOrderId(), $comparison);
        } elseif ($groupOrderSubOrder instanceof ObjectCollection) {
            return $this
                ->useGroupOrderSubOrderQuery()
                ->filterByPrimaryKeys($groupOrderSubOrder->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByGroupOrderSubOrder() only accepts arguments of type \GroupOrder\Model\GroupOrderSubOrder or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the GroupOrderSubOrder relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function joinGroupOrderSubOrder($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('GroupOrderSubOrder');

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
            $this->addJoinObject($join, 'GroupOrderSubOrder');
        }

        return $this;
    }

    /**
     * Use the GroupOrderSubOrder relation GroupOrderSubOrder object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \GroupOrder\Model\GroupOrderSubOrderQuery A secondary query class using the current class as primary query
     */
    public function useGroupOrderSubOrderQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGroupOrderSubOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'GroupOrderSubOrder', '\GroupOrder\Model\GroupOrderSubOrderQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGroupOrder $groupOrder Object to remove from the list of results
     *
     * @return ChildGroupOrderQuery The current query, for fluid interface
     */
    public function prune($groupOrder = null)
    {
        if ($groupOrder) {
            $this->addUsingAlias(GroupOrderTableMap::ID, $groupOrder->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the group_order table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupOrderTableMap::DATABASE_NAME);
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
            GroupOrderTableMap::clearInstancePool();
            GroupOrderTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildGroupOrder or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildGroupOrder object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupOrderTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GroupOrderTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        GroupOrderTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            GroupOrderTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // GroupOrderQuery
