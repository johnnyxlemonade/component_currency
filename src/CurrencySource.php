<?php

namespace Lemonade\CurrencyRate;

interface CurrencySource {

    public function rate($from, $to, \DateTime $date);

}
