<?php
namespace Scheb;

if (version_compare(phpversion(), '5.4', '<')) {
    class CallbackFilterIterator extends \FilterIterator
    {
        protected $callback;

        public function __construct(\Iterator $iterator, \Closure $callback = null)
        {
            $this->callback = $callback;
            parent::__construct($iterator);
        }

        public function accept()
        {
            return call_user_func(
                $this->callback,
                $this->current(),
                $this->key(),
                $this->getInnerIterator()
            );
        }
    }
} else {
    class CallbackFilterIterator extends \CallbackFilterIterator {

    }
}
