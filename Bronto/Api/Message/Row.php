<?php

/**
 * @property-read string $id
 * @property string $name
 * @property string $status
 * @property string $messageFolderId
 * @property array $content
 */
class Bronto_Api_Message_Row extends Bronto_Api_Row
{   
    /**
     * @param Bronto_Api_Deliverygroup_Row|string $deliveryGroup
     * @return bool
     */
    public function addToDeliveryGroup($deliveryGroup)
    {
        if (!$this->id) {
            $exceptionClass = $this->getExceptionClass();
            throw new $exceptionClass("This Message has not been saved yet (has no MessageId)");
        }
        
        $deliveryGroupId = $deliveryGroup;
        if ($deliveryGroup instanceOf Bronto_Api_Deliverygroup_Row) {
            if (!$deliveryGroup->id) {
                $deliveryGroup = $deliveryGroup->read();
            }
            $deliveryGroupId = $deliveryGroup->id;
        }
        
        $deliveryGroupObject = $this->getApiObject()->getApi()->getDeliveryGroupObject();
        return $deliveryGroupObject->addToDeliveryGroup($deliveryGroupId, array(), array($this->id));
    }
    
    /**
     * @param bool $returnData
     * @return Bronto_Api_Message_Row|array
     */
    public function read($returnData = false)
    {
        if ($this->id) {
            $params = array('id' => $this->id);
        } else {
            $params = array(
                'name' => array(
                    'value'    => $this->name,
                    'operator' => 'EqualTo',
                )
            );
        }
        
        return parent::_read($params, $returnData);
    }
    
    /**
     * @param bool $upsert
     * @return Bronto_Api_Message_Row
     */
    public function save($upsert = true)
    {
        if (!$upsert) {
            return parent::save();
        }
        
        try {
            return parent::save();
        } catch (Bronto_Api_Message_Exception $e) {
            if ($e->getCode() == Bronto_Api_Message_Exception::MESSAGE_EXISTS) {
                $this->_refresh();
                return $this->id;
            }
            throw $e;
        }
    }
    
    /**
     * @return bool 
     */
    public function delete()
    {
        return parent::_delete(array('id' => $this->id));
    }
    
    /**
     * Proxy for intellisense
     * 
     * @return Bronto_Api_Message
     */
    public function getApiObject()
    {
        return parent::getApiObject();
    }
}