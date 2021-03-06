<?php

namespace RMT\TimeScheduling\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use FOS\UserBundle\Propel\User;
use RMT\TimeScheduling\Model\Day;
use RMT\TimeScheduling\Model\Reservation;
use RMT\TimeScheduling\Model\ReservationPeer;
use RMT\TimeScheduling\Model\ReservationQuery;

/**
 * @method ReservationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ReservationQuery orderByClientUserId($order = Criteria::ASC) Order by the client_user_id column
 * @method ReservationQuery orderByServiceProviderUserId($order = Criteria::ASC) Order by the service_provider_user_id column
 * @method ReservationQuery orderByDayId($order = Criteria::ASC) Order by the day_id column
 * @method ReservationQuery orderByStartTime($order = Criteria::ASC) Order by the start_time column
 * @method ReservationQuery orderByEndTime($order = Criteria::ASC) Order by the end_time column
 *
 * @method ReservationQuery groupById() Group by the id column
 * @method ReservationQuery groupByClientUserId() Group by the client_user_id column
 * @method ReservationQuery groupByServiceProviderUserId() Group by the service_provider_user_id column
 * @method ReservationQuery groupByDayId() Group by the day_id column
 * @method ReservationQuery groupByStartTime() Group by the start_time column
 * @method ReservationQuery groupByEndTime() Group by the end_time column
 *
 * @method ReservationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ReservationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ReservationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ReservationQuery leftJoinClient($relationAlias = null) Adds a LEFT JOIN clause to the query using the Client relation
 * @method ReservationQuery rightJoinClient($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Client relation
 * @method ReservationQuery innerJoinClient($relationAlias = null) Adds a INNER JOIN clause to the query using the Client relation
 *
 * @method ReservationQuery leftJoinServiceProvider($relationAlias = null) Adds a LEFT JOIN clause to the query using the ServiceProvider relation
 * @method ReservationQuery rightJoinServiceProvider($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ServiceProvider relation
 * @method ReservationQuery innerJoinServiceProvider($relationAlias = null) Adds a INNER JOIN clause to the query using the ServiceProvider relation
 *
 * @method ReservationQuery leftJoinDay($relationAlias = null) Adds a LEFT JOIN clause to the query using the Day relation
 * @method ReservationQuery rightJoinDay($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Day relation
 * @method ReservationQuery innerJoinDay($relationAlias = null) Adds a INNER JOIN clause to the query using the Day relation
 *
 * @method Reservation findOne(PropelPDO $con = null) Return the first Reservation matching the query
 * @method Reservation findOneOrCreate(PropelPDO $con = null) Return the first Reservation matching the query, or a new Reservation object populated from the query conditions when no match is found
 *
 * @method Reservation findOneByClientUserId(int $client_user_id) Return the first Reservation filtered by the client_user_id column
 * @method Reservation findOneByServiceProviderUserId(int $service_provider_user_id) Return the first Reservation filtered by the service_provider_user_id column
 * @method Reservation findOneByDayId(int $day_id) Return the first Reservation filtered by the day_id column
 * @method Reservation findOneByStartTime(string $start_time) Return the first Reservation filtered by the start_time column
 * @method Reservation findOneByEndTime(string $end_time) Return the first Reservation filtered by the end_time column
 *
 * @method array findById(int $id) Return Reservation objects filtered by the id column
 * @method array findByClientUserId(int $client_user_id) Return Reservation objects filtered by the client_user_id column
 * @method array findByServiceProviderUserId(int $service_provider_user_id) Return Reservation objects filtered by the service_provider_user_id column
 * @method array findByDayId(int $day_id) Return Reservation objects filtered by the day_id column
 * @method array findByStartTime(string $start_time) Return Reservation objects filtered by the start_time column
 * @method array findByEndTime(string $end_time) Return Reservation objects filtered by the end_time column
 */
abstract class BaseReservationQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseReservationQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'RMT\\TimeScheduling\\Model\\Reservation', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ReservationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ReservationQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ReservationQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ReservationQuery) {
            return $criteria;
        }
        $query = new ReservationQuery();
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
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Reservation|Reservation[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ReservationPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ReservationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Reservation A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Reservation A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `client_user_id`, `service_provider_user_id`, `day_id`, `start_time`, `end_time` FROM `reservation` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Reservation();
            $obj->hydrate($row);
            ReservationPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Reservation|Reservation[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Reservation[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ReservationPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ReservationPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ReservationPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ReservationPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ReservationPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the client_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByClientUserId(1234); // WHERE client_user_id = 1234
     * $query->filterByClientUserId(array(12, 34)); // WHERE client_user_id IN (12, 34)
     * $query->filterByClientUserId(array('min' => 12)); // WHERE client_user_id >= 12
     * $query->filterByClientUserId(array('max' => 12)); // WHERE client_user_id <= 12
     * </code>
     *
     * @see       filterByClient()
     *
     * @param     mixed $clientUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function filterByClientUserId($clientUserId = null, $comparison = null)
    {
        if (is_array($clientUserId)) {
            $useMinMax = false;
            if (isset($clientUserId['min'])) {
                $this->addUsingAlias(ReservationPeer::CLIENT_USER_ID, $clientUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($clientUserId['max'])) {
                $this->addUsingAlias(ReservationPeer::CLIENT_USER_ID, $clientUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ReservationPeer::CLIENT_USER_ID, $clientUserId, $comparison);
    }

    /**
     * Filter the query on the service_provider_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByServiceProviderUserId(1234); // WHERE service_provider_user_id = 1234
     * $query->filterByServiceProviderUserId(array(12, 34)); // WHERE service_provider_user_id IN (12, 34)
     * $query->filterByServiceProviderUserId(array('min' => 12)); // WHERE service_provider_user_id >= 12
     * $query->filterByServiceProviderUserId(array('max' => 12)); // WHERE service_provider_user_id <= 12
     * </code>
     *
     * @see       filterByServiceProvider()
     *
     * @param     mixed $serviceProviderUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function filterByServiceProviderUserId($serviceProviderUserId = null, $comparison = null)
    {
        if (is_array($serviceProviderUserId)) {
            $useMinMax = false;
            if (isset($serviceProviderUserId['min'])) {
                $this->addUsingAlias(ReservationPeer::SERVICE_PROVIDER_USER_ID, $serviceProviderUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($serviceProviderUserId['max'])) {
                $this->addUsingAlias(ReservationPeer::SERVICE_PROVIDER_USER_ID, $serviceProviderUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ReservationPeer::SERVICE_PROVIDER_USER_ID, $serviceProviderUserId, $comparison);
    }

    /**
     * Filter the query on the day_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDayId(1234); // WHERE day_id = 1234
     * $query->filterByDayId(array(12, 34)); // WHERE day_id IN (12, 34)
     * $query->filterByDayId(array('min' => 12)); // WHERE day_id >= 12
     * $query->filterByDayId(array('max' => 12)); // WHERE day_id <= 12
     * </code>
     *
     * @see       filterByDay()
     *
     * @param     mixed $dayId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function filterByDayId($dayId = null, $comparison = null)
    {
        if (is_array($dayId)) {
            $useMinMax = false;
            if (isset($dayId['min'])) {
                $this->addUsingAlias(ReservationPeer::DAY_ID, $dayId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dayId['max'])) {
                $this->addUsingAlias(ReservationPeer::DAY_ID, $dayId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ReservationPeer::DAY_ID, $dayId, $comparison);
    }

    /**
     * Filter the query on the start_time column
     *
     * Example usage:
     * <code>
     * $query->filterByStartTime('2011-03-14'); // WHERE start_time = '2011-03-14'
     * $query->filterByStartTime('now'); // WHERE start_time = '2011-03-14'
     * $query->filterByStartTime(array('max' => 'yesterday')); // WHERE start_time > '2011-03-13'
     * </code>
     *
     * @param     mixed $startTime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function filterByStartTime($startTime = null, $comparison = null)
    {
        if (is_array($startTime)) {
            $useMinMax = false;
            if (isset($startTime['min'])) {
                $this->addUsingAlias(ReservationPeer::START_TIME, $startTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($startTime['max'])) {
                $this->addUsingAlias(ReservationPeer::START_TIME, $startTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ReservationPeer::START_TIME, $startTime, $comparison);
    }

    /**
     * Filter the query on the end_time column
     *
     * Example usage:
     * <code>
     * $query->filterByEndTime('2011-03-14'); // WHERE end_time = '2011-03-14'
     * $query->filterByEndTime('now'); // WHERE end_time = '2011-03-14'
     * $query->filterByEndTime(array('max' => 'yesterday')); // WHERE end_time > '2011-03-13'
     * </code>
     *
     * @param     mixed $endTime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function filterByEndTime($endTime = null, $comparison = null)
    {
        if (is_array($endTime)) {
            $useMinMax = false;
            if (isset($endTime['min'])) {
                $this->addUsingAlias(ReservationPeer::END_TIME, $endTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($endTime['max'])) {
                $this->addUsingAlias(ReservationPeer::END_TIME, $endTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ReservationPeer::END_TIME, $endTime, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ReservationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByClient($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(ReservationPeer::CLIENT_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ReservationPeer::CLIENT_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByClient() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Client relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function joinClient($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Client');

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
            $this->addJoinObject($join, 'Client');
        }

        return $this;
    }

    /**
     * Use the Client relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FOS\UserBundle\Propel\UserQuery A secondary query class using the current class as primary query
     */
    public function useClientQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinClient($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Client', '\FOS\UserBundle\Propel\UserQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ReservationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByServiceProvider($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(ReservationPeer::SERVICE_PROVIDER_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ReservationPeer::SERVICE_PROVIDER_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByServiceProvider() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ServiceProvider relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function joinServiceProvider($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ServiceProvider');

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
            $this->addJoinObject($join, 'ServiceProvider');
        }

        return $this;
    }

    /**
     * Use the ServiceProvider relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FOS\UserBundle\Propel\UserQuery A secondary query class using the current class as primary query
     */
    public function useServiceProviderQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinServiceProvider($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ServiceProvider', '\FOS\UserBundle\Propel\UserQuery');
    }

    /**
     * Filter the query by a related Day object
     *
     * @param   Day|PropelObjectCollection $day The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ReservationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDay($day, $comparison = null)
    {
        if ($day instanceof Day) {
            return $this
                ->addUsingAlias(ReservationPeer::DAY_ID, $day->getId(), $comparison);
        } elseif ($day instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ReservationPeer::DAY_ID, $day->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDay() only accepts arguments of type Day or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Day relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function joinDay($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Day');

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
            $this->addJoinObject($join, 'Day');
        }

        return $this;
    }

    /**
     * Use the Day relation Day object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \RMT\TimeScheduling\Model\DayQuery A secondary query class using the current class as primary query
     */
    public function useDayQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDay($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Day', '\RMT\TimeScheduling\Model\DayQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Reservation $reservation Object to remove from the list of results
     *
     * @return ReservationQuery The current query, for fluid interface
     */
    public function prune($reservation = null)
    {
        if ($reservation) {
            $this->addUsingAlias(ReservationPeer::ID, $reservation->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
