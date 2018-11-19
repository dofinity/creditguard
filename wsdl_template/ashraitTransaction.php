<?php

class ashraitTransaction
{

    /**
     * @var string $user
     */
    protected $user = null;

    /**
     * @var string $password
     */
    protected $password = null;

    /**
     * @var string $int_in
     */
    protected $int_in = null;

    /**
     * @param string $user
     * @param string $password
     * @param string $int_in
     */
    public function __construct($user, $password, $int_in)
    {
      $this->user = $user;
      $this->password = $password;
      $this->int_in = $int_in;
    }

    /**
     * @return string
     */
    public function getUser()
    {
      return $this->user;
    }

    /**
     * @param string $user
     * @return ashraitTransaction
     */
    public function setUser($user)
    {
      $this->user = $user;
      return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
      return $this->password;
    }

    /**
     * @param string $password
     * @return ashraitTransaction
     */
    public function setPassword($password)
    {
      $this->password = $password;
      return $this;
    }

    /**
     * @return string
     */
    public function getInt_in()
    {
      return $this->int_in;
    }

    /**
     * @param string $int_in
     * @return ashraitTransaction
     */
    public function setInt_in($int_in)
    {
      $this->int_in = $int_in;
      return $this;
    }

}
