<?php
class Model_Foo
{
    /**
     * @var string
     */
    private $foo;

    /**
     * @var string
     */
    private $bar;

    /**
     * @var string
     */
    private $baz;

    public function __construct($foo, $bar, $baz)
    {
        $this->setFoo($foo)
             ->setBar($bar)
             ->setBaz($baz);
    }

    /**
     * @return string
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param string $foo
     * @return Application_Model_Foo
     */
    public function setFoo($foo)
    {
        $this->foo = (string) $foo;
        return $this;
    }

    /**
     * @return string
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param string $bar
     * @return Application_Model_Foo
     */
    public function setBar($bar)
    {
        $this->bar = (string) $bar;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @param string $baz
     * @return Application_Model_Foo
     */
    public function setBaz($baz)
    {
        $this->baz = (string) $baz;
        return $this;
    }

}