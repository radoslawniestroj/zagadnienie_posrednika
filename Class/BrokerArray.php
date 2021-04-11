<?php

class BrokerArray
{
    private $supply;
    private $tempSupply;
    private $purchaseCost;

    private $demand;
    private $tempDemand;
    private $sellingCost;

    private $profit;

    private $transportCost;

    private $unitProfit;

    private $alpha;
    private $beta;

    private $income;

    private $delta;
    private $negativeDelta;

    private $optimizeCounter;

    function __construct(
        $supply,
        $purchaseCost,
        $demand,
        $sellingCost,
        $transportCost
    ) {
        $supply[4] = $demand[0] + $demand[1];
        $this->supply = $supply;
        $this->tempSupply = $supply;
        $this->purchaseCost = $purchaseCost;


        $demand[2] = $supply[0] + $supply[1] + $supply[2] + $supply[3];
        $this->demand = $demand;
        $this->tempDemand = $demand;
        $this->sellingCost = $sellingCost;

        $this->transportCost = $transportCost;

        $this->optimizeCounter = 0;

        $this->countProfit();
        $this->countSolution();

        echo $this->description();

        echo $this->generateBasicTable();
        echo $this->generateTable("Tabela niezoptymalizowana");

        $this->optimize();
    }

    public function countProfit()
    {
        $this->profit[0][0] = $this->sellingCost[0] - $this->transportCost[0][0] - $this->purchaseCost[0];
        $this->profit[0][1] = $this->sellingCost[1] - $this->transportCost[0][1] - $this->purchaseCost[0];

        $this->profit[1][0] = $this->sellingCost[0] - $this->transportCost[1][0] - $this->purchaseCost[1];
        $this->profit[1][1] = $this->sellingCost[1] - $this->transportCost[1][1] - $this->purchaseCost[1];

        $this->profit[2][0] = $this->sellingCost[0] - $this->transportCost[2][0] - $this->purchaseCost[2];
        $this->profit[2][1] = $this->sellingCost[1] - $this->transportCost[2][1] - $this->purchaseCost[2];

        $this->profit[3][0] = $this->sellingCost[0] - $this->transportCost[3][0] - $this->purchaseCost[3];
        $this->profit[3][1] = $this->sellingCost[1] - $this->transportCost[3][1] - $this->purchaseCost[3];
    }

    public function countSolution()
    {
        $tr = 0;
        $th = 0;

        while ($tr < 5) {
            while ($th < 3) {
                if ($this->tempSupply[$tr] >= $this->tempDemand[$th]) {
                    $this->unitProfit[$tr][$th] = $this->tempDemand[$th];
                    $this->tempSupply[$tr] -= $this->tempDemand[$th];
                    $this->tempDemand[$th] = 0;
                } else if ($this->tempSupply[$tr] < $this->tempDemand[$th]) {
                    $this->unitProfit[$tr][$th] = $this->tempSupply[$tr];
                    $this->tempDemand[$th] -= $this->tempSupply[$tr];
                    $this->tempSupply[$tr] = 0;
                }
                $th++;
            }
            $th = 0;
            $tr++;
        }
        $this->count();
    }

    public function optimize() {
        if (!$this->negativeDelta) {
            $path = false;
            $tr = 0;
            $th = 0;

            while ($tr < 5) {
                while ($th < 3) {
                    if ($this->delta[$tr][$th] > 0) {
                        $coordinates = [];

                        $tempTr = $tr;
                        $tempTh = $th;
                        // prawo, dół, lewo ,góra
                        if (!$path) {
                            while($tempTh < 3) {
                                if ($this->delta[$tempTr][$tempTh] === null) {
                                    $coordinates[0] = [$tempTr, $tempTh];

                                    $tempTr++;
                                    while($tempTr < 5) {
                                        $tempTempTh = $tempTh;
                                        if ($this->delta[$tempTr][$tempTh] === null) {
                                            $coordinates[1] = [$tempTr, $tempTh];

                                            $tempTh--;
                                            while($tempTh >= 0) {
                                                $tempTempTr = $tempTr;
                                                if ($this->delta[$tempTr][$tempTh] === null) {
                                                    $coordinates[2] = [$tempTr, $tempTh];

                                                    $tempTr--;
                                                    while($tempTr >= 0) {
                                                        if ($tempTr === $tr && $tempTh === $th) {
                                                            $coordinates[3] = [$tempTr, $tempTh];
                                                            $path = true;
                                                        }

                                                        if ($path) {
                                                            break;
                                                        }
                                                        $tempTr--;
                                                    }
                                                }

                                                if ($path) {
                                                    break;
                                                }
                                                $tempTh--;
                                                $tempTr = $tempTempTr;
                                            }
                                        }

                                        if ($path) {
                                            break;
                                        }
                                        $tempTr++;
                                        $tempTh = $tempTempTh;
                                    }
                                }

                                if ($path) {
                                    break;
                                }
                                $tempTh++;
                            }
                        }


                        $tempTr = $tr;
                        $tempTh = $th;
                        // dół, lewo ,góra, prawo
                        if (!$path) {
                            while($tempTr < 5) {
                                if ($this->delta[$tempTr][$tempTh] === null) {
                                    $coordinates[0] = [$tempTr, $tempTh];

                                    $tempTh--;
                                    while($tempTh >= 0) {
                                        $tempTempTr = $tempTr;
                                        if ($this->delta[$tempTr][$tempTh] === null) {
                                            $coordinates[1] = [$tempTr, $tempTh];

                                            $tempTr--;
                                            while($tempTr >= 0) {
                                                $tempTempTh = $tempTh;
                                                if ($this->delta[$tempTr][$tempTh] === null) {
                                                    $coordinates[2] = [$tempTr, $tempTh];

                                                    $tempTh++;
                                                    while($tempTh < 3) {
                                                        if ($tempTr === $tr && $tempTh === $th) {
                                                            $coordinates[3] = [$tempTr, $tempTh];
                                                            $path = true;
                                                        }

                                                        if ($path) {
                                                            break;
                                                        }
                                                        $tempTh++;
                                                    }
                                                }

                                                if ($path) {
                                                    break;
                                                }
                                                $tempTr--;
                                                $tempTh = $tempTempTh;
                                            }
                                        }

                                        if ($path) {
                                            break;
                                        }
                                        $tempTh--;
                                        $tempTr = $tempTempTr;
                                    }
                                }

                                if ($path) {
                                    break;
                                }
                                $tempTr++;
                            }
                        }


                        $tempTr = $tr;
                        $tempTh = $th;
                        // lewo ,góra, prawo, dół
                        if (!$path) {
                            while($tempTh >= 0) {
                                if ($this->delta[$tempTr][$tempTh] === null) {
                                    $coordinates[0] = [$tempTr, $tempTh];

                                    $tempTr--;
                                    while($tempTr >= 0) {
                                        $tempTempTh = $tempTh;
                                        if ($this->delta[$tempTr][$tempTh] === null) {
                                            $coordinates[1] = [$tempTr, $tempTh];

                                            $tempTh++;
                                            while($tempTh < 3) {
                                                $tempTempTr = $tempTr;
                                                if ($this->delta[$tempTr][$tempTh] === null) {
                                                    $coordinates[2] = [$tempTr, $tempTh];

                                                    $tempTr++;
                                                    while($tempTr < 5) {
                                                        if ($tempTr === $tr && $tempTh === $th) {
                                                            $coordinates[3] = [$tempTr, $tempTh];
                                                            $path = true;
                                                        }

                                                        if ($path) {
                                                            break;
                                                        }
                                                        $tempTr++;
                                                    }
                                                }

                                                if ($path) {
                                                    break;
                                                }
                                                $tempTh++;
                                                $tempTr = $tempTempTr;
                                            }
                                        }

                                        if ($path) {
                                            break;
                                        }
                                        $tempTr--;
                                        $tempTh = $tempTempTh;
                                    }
                                }

                                if ($path) {
                                    break;
                                }
                                $tempTh--;
                            }
                        }



                        $tempTr = $tr;
                        $tempTh = $th;
                        // góra, prawo, dół, lewo
                        if (!$path) {
                            while($tempTr >= 0) {
                                if ($this->delta[$tempTr][$tempTh] === null) {
                                    $coordinates[0] = [$tempTr, $tempTh];

                                    $tempTh++;
                                    while($tempTh < 3) {
                                        $tempTempTr = $tempTr;
                                        if ($this->delta[$tempTr][$tempTh] === null) {
                                            $coordinates[1] = [$tempTr, $tempTh];

                                            $tempTr++;
                                            while($tempTr < 5) {
                                                $tempTempTh = $tempTh;
                                                if ($this->delta[$tempTr][$tempTh] === null) {
                                                    $coordinates[2] = [$tempTr, $tempTh];

                                                    $tempTh--;
                                                    while($tempTh >= 0) {
                                                        if ($tempTr === $tr && $tempTh === $th) {
                                                            $coordinates[3] = [$tempTr, $tempTh];
                                                            $path = true;
                                                        }

                                                        if ($path) {
                                                            break;
                                                        }
                                                        $tempTh--;
                                                    }
                                                }

                                                if ($path) {
                                                    break;
                                                }
                                                $tempTr++;
                                                $tempTh = $tempTempTh;
                                            }
                                        }

                                        if ($path) {
                                            break;
                                        }
                                        $tempTh++;
                                        $tempTr = $tempTempTr;
                                    }
                                }

                                if ($path) {
                                    break;
                                }
                                $tempTr--;
                                $tempTh = $th;
                            }
                        }


                        if ($path) {
                            if ((int) $this->unitProfit[$coordinates[0][0]][$coordinates[0][1]] !== 0) {
                                $value = $this->unitProfit[$coordinates[0][0]][$coordinates[0][1]];
                                $this->unitProfit[$coordinates[0][0]][$coordinates[0][1]] -= $value;
                                $this->unitProfit[$coordinates[1][0]][$coordinates[1][1]] += $value;
                                $this->unitProfit[$coordinates[2][0]][$coordinates[2][1]] -= $value;
                                $this->unitProfit[$coordinates[3][0]][$coordinates[3][1]] += $value;
                            } else if ((int) $this->unitProfit[$coordinates[2][0]][$coordinates[2][1]] !== 0) {
                                $value = $this->unitProfit[$coordinates[2][0]][$coordinates[2][1]];
                                $this->unitProfit[$coordinates[0][0]][$coordinates[0][1]] -= $value;
                                $this->unitProfit[$coordinates[1][0]][$coordinates[1][1]] += $value;
                                $this->unitProfit[$coordinates[2][0]][$coordinates[2][1]] -= $value;
                                $this->unitProfit[$coordinates[3][0]][$coordinates[3][1]] += $value;
                            }
                        }
                    }
                    if ($path) {
                        break;
                    }
                    $th++;
                }
                if ($path) {
                    break;
                }
                $th = 0;
                $tr++;
            }

            $tempIncome = $this->income;
            $this->count();
            if ($tempIncome > $this->income) {
                $this->income = $tempIncome;

                echo '<div class="process-message-div"><h2 class="title error-small">Optymalizacja okazała się gorszym rozwiązaniem</h2></div>';
            } else if ($tempIncome <= $this->income) {
                echo $this->generateTable("Tabela zoptymalizowana");
            }
        }

        if ($this->negativeDelta) {
            echo '<div class="process-complete-div"><h2 class="title process-complete">Delta jest niedodatnia, proces zakończony sukcesem</h2></div>';
        } else {
            if ($this->optimizeCounter < 100) {
                $this->optimize();
                $this->optimizeCounter++;
            } else {
                echo '<div class="process-complete-div"><h2 class="title error">Proces został przerwany, za dużo razy została użyta funkcja optimize</h2></div>';
            }
        }
    }

    public function count() {
        $this->alpha = [];
        $this->beta = [];
        $this->delta = [];
        $this->income = 0;
        $this->negativeDelta = true;

        $tr = 0;
        $th = 0;

        $this->alpha[0] = 0;
        while ($tr < 5) {
            while ($th < 3) {
                if ($this->unitProfit[$tr][$th] !== 0) {
                    if ($this->alpha[$tr] !== null) {
                        $this->beta[$th] = $this->profit[$tr][$th] - $this->alpha[$tr];
                    } else if ($this->beta[$th] !== null) {
                        $this->alpha[$tr] = $this->profit[$tr][$th] - $this->beta[$th];
                    }
                }
                $th++;
            }
            $th = 0;
            $tr++;
        }


        $tr = 0;
        $th = 0;

        while ($th < 3) {
            while ($tr < 5) {
                if ($this->unitProfit[$tr][$th] !== 0) {
                    if ($this->alpha[$tr] !== null) {
                        $this->beta[$th] = $this->profit[$tr][$th] - $this->alpha[$tr];
                    } else if ($this->beta[$th] !== null) {
                        $this->alpha[$tr] = $this->profit[$tr][$th] - $this->beta[$th];
                    }
                }
                $tr++;
            }
            $tr = 0;
            $th++;
        }


        $tr = 0;
        $th = 0;
        while ($tr < 5) {
            while ($th < 3) {
                if ($this->unitProfit[$tr][$th] === 0) {
                    $this->delta[$tr][$th] = $this->profit[$tr][$th] - $this->alpha[$tr] - $this->beta[$th];
                    if ($this->delta[$tr][$th] > 0) {
                        $this->negativeDelta = false;
                    }
                }
                $this->income += $this->profit[$tr][$th] * $this->unitProfit[$tr][$th];
                $th++;
            }
            $th = 0;
            $tr++;
        }
    }

    public function generateBasicTable()
    {
        return
            '<div class="basic-div">
                <h2 class="title table-title">Tabela podstawowa</h2>
                <table class="table">
                    <tr>
                      <th></th>
                      <th>O1 ' . $this->demand[0] . '</th>
                      <th>O2 ' . $this->demand[1] . '</th>
                      <th>FO ' . $this->demand[2] . '</th>
                    </tr>
                    <tr>
                      <th>D1 ' . $this->supply[0] . '</th>
                      <td>' . $this->profit[0][0] . '</td>
                      <td>' . $this->profit[0][1] . '</td>
                      <td>0</td>
                    </tr>
                    <tr>
                      <th>D2 ' . $this->supply[1] . '</th>
                      <td>' . $this->profit[1][0] . '</td>
                      <td>' . $this->profit[1][1] . '</td>
                      <td>0</td>
                    </tr>
                    <tr>
                      <th>D3 ' . $this->supply[2] . '</th>
                      <td>' . $this->profit[2][0] . '</td>
                      <td>' . $this->profit[2][1] . '</td>
                      <td>0</td>
                    </tr>
                    <tr>
                      <th>D4 ' . $this->supply[3] . '</th>
                      <td>' . $this->profit[3][0] . '</td>
                      <td>' . $this->profit[3][1] . '</td>
                      <td>0</td>
                    </tr>
                    <tr>
                      <th>FD ' . $this->supply[4] . '</th>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                    </tr>
                </table>
            </div>';
    }

    public function generateTable($message)
    {
        return
            '<div class="solution-div">
                <h2 class="title table-title">' . $message . '</h2>
                <table class="table unoptimized">
                    <tr>
                      <th></th>
                      <th>O1 ' . $this->tempDemand[0] . '</th>
                      <th>O2 ' . $this->tempDemand[1] . '</th>
                      <th>FO ' . $this->tempDemand[2] . '</th>
                      <th>alpha</th>
                    </tr>
                    <tr>
                      <th>D1 ' . $this->tempSupply[0] . '</th>
                      <td>' . $this->unitProfit[0][0] . '</td>
                      <td>' . $this->unitProfit[0][1] . '</td>
                      <td>' . $this->unitProfit[0][2] . '</td>
                      <td>' . $this->alpha[0] . '</td>
                    </tr>
                    <tr>
                      <th>D2 ' . $this->tempSupply[1] . '</th>
                      <td>' . $this->unitProfit[1][0] . '</td>
                      <td>' . $this->unitProfit[1][1] . '</td>
                      <td>' . $this->unitProfit[1][2] . '</td>
                      <td>' . $this->alpha[1] . '</td>
                    </tr>
                    <tr>
                      <th>D3 ' . $this->tempSupply[2] . '</th>
                      <td>' . $this->unitProfit[2][0] . '</td>
                      <td>' . $this->unitProfit[2][1] . '</td>
                      <td>' . $this->unitProfit[2][2] . '</td>
                      <td>' . $this->alpha[2] . '</td>
                    </tr>
                    <tr>
                      <th>D4 ' . $this->tempSupply[3] . '</th>
                      <td>' . $this->unitProfit[3][0] . '</td>
                      <td>' . $this->unitProfit[3][1] . '</td>
                      <td>' . $this->unitProfit[3][2] . '</td>
                      <td>' . $this->alpha[3] . '</td>
                    </tr>
                    <tr>
                      <th>FD ' . $this->tempSupply[4] . '</th>
                      <td>' . $this->unitProfit[4][0] . '</td>
                      <td>' . $this->unitProfit[4][1] . '</td>
                      <td>' . $this->unitProfit[4][2] . '</td>
                      <td>' . $this->alpha[4] . '</td>
                    </tr>
                    <tr>
                      <th>beta</th>
                      <td>' . $this->beta[0] . '</td>
                      <td>' . $this->beta[1] . '</td>
                      <td>' . $this->beta[2] . '</td>
                      <td></td>
                    </tr>
                </table>
                
                <table class="table">
                    <tr>
                      <td>' . $this->delta[0][0] . '</td>
                      <td>' . $this->delta[0][1] . '</td>
                      <td>' . $this->delta[0][2] . '</td>
                    </tr>
                    <tr>
                      <td>' . $this->delta[1][0] . '</td>
                      <td>' . $this->delta[1][1] . '</td>
                      <td>' . $this->delta[1][2] . '</td>
                    </tr>
                    <tr>
                      <td>' . $this->delta[2][0] . '</td>
                      <td>' . $this->delta[2][1] . '</td>
                      <td>' . $this->delta[2][2] . '</td>
                    </tr>
                    <tr>
                      <td>' . $this->delta[3][0] . '</td>
                      <td>' . $this->delta[3][1] . '</td>
                      <td>' . $this->delta[3][2] . '</td>
                    </tr>
                    <tr>
                      <td>' . $this->delta[4][0] . '</td>
                      <td>' . $this->delta[4][1] . '</td>
                      <td>' . $this->delta[4][2] . '</td>
                    </tr>
                </table>
                <h2 class="title income">Zyski: <span class="income-solution-span">' . $this->income . '</span></h2>
            </div>';
    }

    public function description() {
        return
            '<div class="task-description-div">
                <p class="task-description-p">
                4 dostawców (podaż: ' . $this->supply[0] . ', ' . $this->supply[1] . ', ' . $this->supply[2] . ' i ' . $this->supply[3] . ',
                jednostkowe koszty zakupu: ' . $this->purchaseCost[0] . ', ' . $this->purchaseCost[1] . ', ' . $this->purchaseCost[2] . ' i ' . $this->purchaseCost[3] . '),
                2 odbiorców (popyt: ' . $this->demand[0] . ' i ' . $this->demand[1] . ', ceny sprzedaży: ' . $this->sellingCost[0] . ' i ' . $this->sellingCost[1] . ').</p>
                <h2 class="title table-title">Jednostkowe koszty transportu</h2>
                <table class="table">
                    <tr>
                      <th></th>
                      <th>O1</th>
                      <th>O2</th>
                    </tr>
                    <tr>
                      <th>D1</th>
                      <td>' . $this->transportCost[0][0] . '</td>
                      <td>' . $this->transportCost[0][1] . '</td>
                    </tr>
                    <tr>
                      <th>D2</th>
                      <td>' . $this->transportCost[1][0] . '</td>
                      <td>' . $this->transportCost[1][1] . '</td>
                    </tr>
                    <tr>
                      <th>D3</th>
                      <td>' . $this->transportCost[2][0] . '</td>
                      <td>' . $this->transportCost[2][1] . '</td>
                    </tr>
                    <tr>
                      <th>D4</th>
                      <td>' . $this->transportCost[3][0] . '</td>
                      <td>' . $this->transportCost[3][1] . '</td>
                    </tr>
                </table>
            </div>';
    }
}

