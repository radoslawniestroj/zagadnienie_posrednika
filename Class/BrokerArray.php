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

    private $optimization;

    function __construct(
        $supply,
        $purchaseCost,
        $demand,
        $sellingCost,
        $transportCost
    )
    {
        $supply[4] = $demand[0] + $demand[1];
        $this->supply = $supply;
        $this->tempSupply = $supply;
        $this->purchaseCost = $purchaseCost;


        $demand[2] = $supply[0] + $supply[1] + $supply[2] + $supply[3];
        $this->demand = $demand;
        $this->tempDemand = $demand;
        $this->sellingCost = $sellingCost;

        $this->transportCost = $transportCost;

        $this->countProfit();
        $this->countSolution();

        echo $this->generateBasicTable();
        echo $this->generateTable("Tabela niezoptymalizowana");

        $this->optimize();

        echo $this->generateTable("Tabela zoptymalizowana");
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
        $tr = 0;
        $th = 0;

        while ($tr < 5) {
            while ($th < 3) {
                if ($this->optimization[$tr][$th] > 0) {
                    if ($th-1 >= 0) {
                        if ($this->unitProfit[$tr][$th-1] !== 0) {
                            $value = $this->unitProfit[$tr][$th-1];
                            $this->unitProfit[$tr][$th-1] -= $value;
                            $this->unitProfit[$tr][$th] += $value;
                            $this->unitProfit[$tr+1][$th] -= $value;
                            $this->unitProfit[$tr+1][$th-1] += $value;

                        }
                    }
                }
                $th++;
            }
            $th = 0;
            $tr++;
        }

        $this->count();
    }

    public function count() {
        $tr = 0;
        $th = 0;

        $this->alpha = [];
        $this->beta = [];
        $this->optimization = [];

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
        while ($tr < 5) {
            while ($th < 3) {
                if ($this->unitProfit[$tr][$th] === 0) {
                    $this->optimization[$tr][$th] = $this->profit[$tr][$th] - $this->alpha[$tr] - $this->beta[$th];
                }
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
                <h2>Tabela podstawowa</h2>
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
                <h2>' . $message . '</h2>
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
                      <td>' . $this->optimization[0][0] . '</td>
                      <td>' . $this->optimization[0][1] . '</td>
                      <td>' . $this->optimization[0][2] . '</td>
                    </tr>
                    <tr>
                      <td>' . $this->optimization[1][0] . '</td>
                      <td>' . $this->optimization[1][1] . '</td>
                      <td>' . $this->optimization[1][2] . '</td>
                    </tr>
                    <tr>
                      <td>' . $this->optimization[2][0] . '</td>
                      <td>' . $this->optimization[2][1] . '</td>
                      <td>' . $this->optimization[2][2] . '</td>
                    </tr>
                    <tr>
                      <td>' . $this->optimization[3][0] . '</td>
                      <td>' . $this->optimization[3][1] . '</td>
                      <td>' . $this->optimization[3][2] . '</td>
                    </tr>
                    <tr>
                      <td>' . $this->optimization[4][0] . '</td>
                      <td>' . $this->optimization[4][1] . '</td>
                      <td>' . $this->optimization[4][2] . '</td>
                    </tr>
                </table>
            </div>';
    }
}

