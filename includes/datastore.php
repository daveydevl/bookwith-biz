<?php

use Google\Cloud\Datastore\DatastoreClient;
use Google\Cloud\Datastore\Query\Query;

class Datastore {
    private $datastore;
    private $cache = [];
    
    private $user = [
        'subdomain' => '',
        'name'      => '',
        'logo'      => 'https://i.imgur.com/E57xzwk.png',
        'desc'      => '',
        'map_title' => '',
        'map_link'  => '',
        'tel'       => '',
        'color'     => 'Green',
        
        'timezone'  => '',
        'language'  => 'en',
        
        'sun_times' => [
            '0', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00'
        ],
        'mon_times' => [
            '0', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00'
        ],
        'tue_times' => [
            '0', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00'
        ],
        'wed_times' => [
            '0', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00'
        ],
        'thu_times' => [
            '0', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00'
        ],
        'fri_times' => [
            '0', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00'
        ],
        'sat_times' => [
            '0', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00', '09:00', '17:00'
        ],
        
        'slot_size'  => 30,
        
        'sub_expire' => 0,
        'sub_resub'  => 0,
        'sub_active' => false,
        'sub_amt'    => '0.00'
    ];
    
    private $user_fields_unindexed = [
        'name',
        'logo',
        'desc',
        'map_title',
        'map_link',
        'tel',
        'timezone',
        'color',
        'slot_size',
        'language'
    ];
    
    function __construct() {
        $this->datastore = new DatastoreClient([
            'projectId' => 'bookwith-biz'
        ]);
    }
    
    function fetch($kind, $filters) {
        $query = $this->datastore->query()->kind($kind)->limit(1);
        
        foreach ($filters as $filter => $value)
            $query = $query->filter($filter, '=', $value);
        
        foreach ($this->datastore->runQuery($query) as $row)
            return $row;
        
        return null;
    }
    
    function update($entity) {
        $this->datastore->upsert($entity);
        
        return $entity;
    }
    
    function insert($kind, $data, $unindex) {
        $entity = $this->datastore->entity($kind, $data, ['excludeFromIndexes' => $unindex]);
        $this->datastore->insert($entity);
        
        return $entity;
    }
    
    function makeEvent($email, $epoch_start, $epoch_end, $name, $phone) {
        $business = $this->fetchBusinessByEmail($email);
        
        $key = $business->key()->pathElement('events');

        $event = $this->datastore->entity($key, [
            'start' => $epoch_start,
            'end'   => $epoch_end,
            'name'  => $name,
            'phone' => $phone
        ], ['excludeFromIndexes' => ['name', 'phone']]);

        $this->datastore->insert($event);
    }
    
    function getEvents($email, $epoch_start, $epoch_end) {
        $business = $this->fetchBusinessByEmail($email);
        $key = $business->key();

        $query = $this->datastore->query()
            ->kind('events')
            ->hasAncestor($key)
            ->filter('end', '>=', $epoch_start)
            ->filter('end', '<=', $epoch_end)
            ->order('end', Query::ORDER_ASCENDING);
        
        $rows = [];
        foreach ($this->datastore->runQuery($query) as $row)
            $rows[] = $row;
            
        return $rows;
    }
    
    function deleteEvent($email, $id) {
        $business = $this->fetchBusinessByEmail($email);
        $key = $business->key();

        $query = $this->datastore->query()
            ->kind('events')
            ->hasAncestor($key)
            ->filter('start', '=', intval($id))
            ->limit(1);
            
        foreach ($this->datastore->runQuery($query) as $row) {
            $this->datastore->delete($row->key());
            break;
        }
    }
    
    function fetchBusinessByEmail($email) {
        if (!isset($this->cache[$email]))
            $this->cache[$email] = $this->fetch('users', [
                'email' => $email
            ]);
            
        if (empty($this->cache[$email])) {
            $this->user['email'] = $email;
            $this->user['sub_expire'] = time() + 1209600;
            
            $this->cache[$email] = $this->insert('users', $this->user, $this->user_fields_unindexed);
            
            $this->cache[$email]['new_account'] = true;
        }
        
        return $this->cache[$email];
    }
    
    function fetchBusinessByDomain($subdomain) {
        return $this->fetch('users', [
            'subdomain' => $subdomain
        ]);
    }
    
}
