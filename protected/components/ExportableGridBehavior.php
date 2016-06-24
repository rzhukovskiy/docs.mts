<?php
class ExportableGridBehavior extends CBehavior
{
    public $buttonId = 'export-button';		//Id used on the export link
    public $exportParam = 'exportCSV';		//Param to be appended on url to fire an export
    public $filename = 'export.xls';		//Filename suggested to user when downloading
    /** @var PHPExcel $objPHPExcel */
    public $objPHPExcel = null;
    private $headersSent = false;
    private $showCompany = false;
    private $companyType = Company::CARWASH_TYPE;
    private $time = null;
    

    /**
     * @param Act $actModel
     */
    public function exportCSV($actModel)
    {
        if ($this->isExportRequest()) {
            $this->time = strtotime($actModel->month . '-01 00:00:00');
            $this->showCompany = $actModel->showCompany;
            $this->companyType = $actModel->companyType;

            spl_autoload_unregister(array('YiiBase','autoload'));
            Yii::import("ext.PHPExcel.Classes.PHPExcel", true);
            spl_autoload_register(array('YiiBase','autoload'));

            $zip = new ZipArchive();
            $filename = "acts/" . date('m-Y', $this->time) . "/$actModel->companyType.zip";

            if ($zip->open($filename, ZipArchive::OVERWRITE) !== TRUE) {
                $zip = null;
            }

            if ($this->showCompany) {
                foreach($actModel->getClientsByType($this->companyType) as $actPartner) {
                    $actModel->client_id = $actPartner->client_id;
                    $this->fillAct($actModel, $actPartner->client, $zip);
                }
            } else {
                foreach($actModel->getPartnersByType($this->companyType) as $actClient) {
                    $actModel->partner_id = $actClient->partner_id;
                    $this->fillAct($actModel, $actClient->partner, $zip);
                }
            }

            if ($zip) $zip->close();
        }
    }

    /**
     * @param Act $actModel
     * @param Company $company
     * @param ZipArchive $zip
     */
    private function fillAct($actModel, $company, &$zip)
    {
        $dataList = $actModel->search()->getData();
        if (!$dataList) {
            return;
        }

        switch ($this->companyType) {
            case Company::SERVICE_TYPE:
                foreach ($dataList as $data) {
                    $this->generateAct($company, array($data), $zip);
                }
                break;
            default:
                $this->generateAct($company, $dataList, $zip);
        }
    }

    /**
     * @param Company $company
     * @param array $dataList
     * @param ZipArchive $zip
     */
    private function generateAct($company, $dataList, &$zip)
    {
        $this->objPHPExcel = new PHPExcel();
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');

        // Creating a workbook
        $this->objPHPExcel->getProperties()->setCreator('Mtransservice');
        $this->objPHPExcel->getProperties()->setTitle('Акт');
        $this->objPHPExcel->getProperties()->setSubject('Акт');
        $this->objPHPExcel->getProperties()->setDescription('');
        $this->objPHPExcel->getProperties()->setCategory('');
        $this->objPHPExcel->removeSheetByIndex(0);

        //adding worksheet
        $companyWorkSheet = new PHPExcel_Worksheet($this->objPHPExcel, 'акт');
        $this->objPHPExcel->addSheet($companyWorkSheet);

        $companyWorkSheet->getPageMargins()->setTop(2);
        $companyWorkSheet->getPageMargins()->setLeft(0.5);
        $companyWorkSheet->getRowDimension(1)->setRowHeight(1);
        $companyWorkSheet->getRowDimension(10)->setRowHeight(100);
        $companyWorkSheet->getColumnDimension('A')->setWidth(2);
        $companyWorkSheet->getDefaultRowDimension()->setRowHeight(20);

        //headers;
        $monthName = StringNum::getMonthName($this->time);
        $date = date_create(date('Y-m-d', $this->time));
        date_add($date, date_interval_create_from_date_string("1 month"));
        $currentMonthName = StringNum::getMonthName($date->getTimestamp());

        $companyWorkSheet->getStyle('B2:I4')->applyFromArray(array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        ));
        $companyWorkSheet->mergeCells('B2:I2');
        if($company->is_split) {
            $companyWorkSheet->mergeCells('B2:J2');
        }
        $text = "АКТ СДАЧИ-ПРИЕМКИ РАБОТ (УСЛУГ)";
        $companyWorkSheet->setCellValue('B2', $text);
        $companyWorkSheet->mergeCells('B3:I3');
        $text = "по договору на оказание услуг $company->contract";
        $companyWorkSheet->setCellValue('B3', $text);
        $companyWorkSheet->mergeCells('B4:I4');
        $text = "За услуги, оказанные в $monthName[2] " . date('Y', $this->time) . ".";
        $companyWorkSheet->setCellValue('B4', $text);

        $companyWorkSheet->setCellValue('B5', 'г.Воронеж');
        $companyWorkSheet->getStyle('H5:I5')->applyFromArray(array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            )
        ));
        $companyWorkSheet->mergeCells('H5:I5');
        if($company->is_split) {
            $companyWorkSheet->mergeCells('H5:J5');
        }
        if ($this->showCompany) {
            $companyWorkSheet->setCellValue('H5', date("t ", $this->time) . $monthName[1] . date(' Y', $this->time));
        } else {
            $companyWorkSheet->setCellValue('H5', date('d ') . $currentMonthName[1] . date(' Y'));
        }

        $companyWorkSheet->mergeCells('B8:I8');
        $companyWorkSheet->mergeCells('B7:I7');
        if ($this->showCompany) {
            $companyWorkSheet->setCellValue('B8', "Исполнитель: ООО «Международный Транспортный Сервис»");
            $companyWorkSheet->setCellValue('B7', "Заказчик: $company->name");
        } else {
            $companyWorkSheet->setCellValue('B7', "Исполнитель: $company->name");
            $companyWorkSheet->setCellValue('B8', "Заказчик: ООО «Международный Транспортный Сервис»");
        }

        $companyWorkSheet->mergeCells('B10:I10');
        $companyWorkSheet->getStyle('B10:I10')->getAlignment()->setWrapText(true);
        $companyWorkSheet->getStyle('B10:I10')->applyFromArray(array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
            )
        ));
        $companyWorkSheet->setCellValue('B10', $company->act_header);


        //main values
        $row = 12;
        $num = 0;
        $total = 0;
        if ($this->companyType != Company::CARWASH_TYPE) {
            $first = $dataList[0];
            $companyWorkSheet->setCellValue('H5', date("d ", strtotime($first->service_date)) . $monthName[1] . date(' Y', $this->time));

            $row = 11;

            $companyWorkSheet->getDefaultStyle()->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                )
            ));
            $companyWorkSheet->getColumnDimension('B')->setWidth(10);
            $companyWorkSheet->getColumnDimension('C')->setWidth(10);
            $companyWorkSheet->getColumnDimension('D')->setWidth(10);
            $companyWorkSheet->getColumnDimension('E')->setWidth(10);
            $companyWorkSheet->getColumnDimension('F')->setWidth(10);
            $companyWorkSheet->getColumnDimension('G')->setWidth(10);
            $companyWorkSheet->getColumnDimension('H')->setWidth(10);
            $companyWorkSheet->getColumnDimension('I')->setWidth(10);

            /** @var Act $data */
            foreach ($dataList as $data) {
                $row++;
                $num = 0;

                $companyWorkSheet->mergeCells("B$row:C$row");
                $companyWorkSheet->setCellValue("B$row", "Число");
                $companyWorkSheet->mergeCells("D$row:E$row");
                $companyWorkSheet->setCellValue("D$row", "№ Карты");
                $companyWorkSheet->mergeCells("F$row:G$row");
                $companyWorkSheet->setCellValue("F$row", "Марка ТС");
                $companyWorkSheet->mergeCells("H$row:I$row");
                $companyWorkSheet->setCellValue("H$row", "Госномер");
                $companyWorkSheet->getStyle("B$row:I$row")->applyFromArray(array(
                        'font' => array(
                            'bold' => true,
                            'color' => array('argb' => 'FF006699'),
                        ),
                    )
                );
                $companyWorkSheet->getStyle("B$row:I$row")
                    ->applyFromArray(array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('argb' => 'FF000000'),
                                ),
                            ),
                        )
                    );

                $row++;
                $date = new DateTime($data->service_date);
                $companyWorkSheet->mergeCells("B$row:C$row");
                $companyWorkSheet->setCellValueByColumnAndRow(1, $row, $date->format('j'));
                $companyWorkSheet->mergeCells("D$row:E$row");
                $companyWorkSheet->setCellValueByColumnAndRow(3, $row, $data->card->number);
                $companyWorkSheet->mergeCells("F$row:G$row");
                $companyWorkSheet->setCellValueByColumnAndRow(5, $row, isset($data->mark) ? $data->mark->name : "");
                $companyWorkSheet->mergeCells("H$row:I$row");
                $companyWorkSheet->setCellValueByColumnAndRow(7, $row, $data->number);
                $companyWorkSheet->getStyle("B$row:I$row")
                    ->applyFromArray(array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('argb' => 'FF000000'),
                                ),
                            ),
                        )
                    );

                $row++;
                $companyWorkSheet->mergeCells("B$row:G$row");
                $companyWorkSheet->setCellValue("B$row", "Вид услуг");
                $companyWorkSheet->setCellValue("H$row", "Кол-во");
                $companyWorkSheet->setCellValue("I$row", "Стоимость");
                $companyWorkSheet->getStyle("B$row:I$row")->applyFromArray(array(
                        'font' => array(
                            'bold' => true,
                            'color' => array('argb' => 'FF006699'),
                        ),
                    )
                );

                /** @var ActScope $scope */
                foreach ($data->scope as $scope) {
                    $row++;
                    $num++;
                    $companyWorkSheet->mergeCells("B$row:G$row");
                    $companyWorkSheet->setCellValue("B$row", "$num. $scope->description");
                    $companyWorkSheet->getStyle("B$row:G$row")->getAlignment()->setWrapText(true);
                    if (mb_strlen($scope->description) > 55) {
                        $companyWorkSheet->getRowDimension($row)->setRowHeight(40);
                    }
                    $companyWorkSheet->setCellValue("H$row", $scope->amount);
                    if ($this->showCompany) {
                        $companyWorkSheet->setCellValue("I$row", $scope->income);
                        $total += $scope->amount * $scope->income;
                    } else {
                        $companyWorkSheet->setCellValue("I$row", $scope->expense);
                        $total += $scope->amount * $scope->expense;
                    }
                }

                $companyWorkSheet->getStyle("B13:I$row")
                    ->applyFromArray(array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('argb' => 'FF000000'),
                                ),
                            ),
                        )
                    );
            }
        } else {
            $companyWorkSheet->getColumnDimension('B')->setWidth(5);
            $companyWorkSheet->getColumnDimension('C')->setAutoSize(true);
            $companyWorkSheet->getColumnDimension('D')->setAutoSize(true);
            $companyWorkSheet->getColumnDimension('E')->setAutoSize(true);
            $companyWorkSheet->getColumnDimension('F')->setAutoSize(true);
            $companyWorkSheet->getColumnDimension('G')->setAutoSize(true);
            $companyWorkSheet->getColumnDimension('H')->setAutoSize(true);
            $companyWorkSheet->getColumnDimension('I')->setAutoSize(true);
            if($company->is_split) {
                $companyWorkSheet->getColumnDimension('J')->setAutoSize(true);
            }

            $headers = ['№', 'Число', '№ Карты', 'Марка ТС', 'Госномер', 'Вид услуги', 'Стоимость', '№ Чека'];
            if($company->is_split) {
                $headers = ['№', 'Число', '№ Карты', 'Марка ТС', 'Госномер', 'Прицеп', 'Вид услуги', 'Стоимость', '№ Чека'];
            }
            $companyWorkSheet->fromArray($headers, null, 'B12');
            /** @var Act $data */
            $currentId = 0;
            $isParent = false;
            if ($this->showCompany && count($company->children) > 0) {
                $isParent = true;
            }
            foreach ($dataList as $data) {
                if ($isParent && $currentId != $data->client_id) {
                    $row++;

                    $companyWorkSheet->getStyle("B$row:I$row")->applyFromArray(array(
                            'font' => array(
                                'bold' => true,
                                'color' => array('argb' => 'FF006699'),
                            ),
                        )
                    );

                    $companyWorkSheet->mergeCells("B$row:I$row");
                    $companyWorkSheet->setCellValue("B$row", $data->client->name);
                    $currentId = $data->client_id;
                }

                $row++;
                $num++;
                $column = 1;
                $date = new DateTime($data->service_date);
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $num);
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $date->format('j'));
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $data->card->number);
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, isset($data->mark) ? $data->mark->name : "");
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $data->number);
                if($company->is_split) {
                    $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $data->extra_number);
                }
                if ($this->showCompany) {
                    $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, Act::$fullList[$data->client_service]);
                    $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $data->income);
                    $total += $data->income;
                } else {
                    $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, Act::$fullList[$data->partner_service]);
                    $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $data->expense);
                    $total += $data->expense;
                }
                $companyWorkSheet->getCellByColumnAndRow($column, $row)
                    ->getStyle()
                    ->getNumberFormat()
                    ->setFormatCode(
                        PHPExcel_Style_NumberFormat::FORMAT_TEXT
                    );
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, ' ' . $data->check);
            }

            $companyWorkSheet->getStyle('B12:I12')->applyFromArray(array(
                    'font' => array(
                        'bold' => true,
                        'color' => array('argb' => 'FF006699'),
                    ),
                )
            );
            if($company->is_split) {
                $companyWorkSheet->getStyle('J12')->applyFromArray(array(
                        'font' => array(
                            'bold' => true,
                            'color' => array('argb' => 'FF006699'),
                        ),
                    )
                );
            }

            $companyWorkSheet->getStyle("B12:I$row")
                ->applyFromArray(array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FF000000'),
                        ),
                    ),
                )
            );
            if($company->is_split) {
                $companyWorkSheet->getStyle("J12:J$row")
                    ->applyFromArray(array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('argb' => 'FF000000'),
                                ),
                            ),
                        )
                    );
            }
        }

        //footer
        $row++;
        if ($this->companyType == Company::CARWASH_TYPE) {
            if($company->is_split) {
                $companyWorkSheet->setCellValue("H$row", "ВСЕГО:");
                $companyWorkSheet->setCellValue("I$row", "$total");
            } else {
                $companyWorkSheet->setCellValue("G$row", "ВСЕГО:");
                $companyWorkSheet->setCellValue("H$row", "$total");
            }
        } else {
            $companyWorkSheet->setCellValue("H$row", "ВСЕГО:");
            $companyWorkSheet->setCellValue("I$row", "$total");
        }

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:I$row");
        if($company->is_split) {
            $companyWorkSheet->mergeCells("B$row:J$row");
        }
        $companyWorkSheet->getRowDimension($row)->setRowHeight(30);
        $companyWorkSheet->getStyle("B$row:I$row")->getAlignment()->setWrapText(true);
        $text = "Общая стоимость выполненных услуг составляет: $total (" . StringNum::num2str($total) . ") рублей. НДС нет.";
        $companyWorkSheet->setCellValue("B$row", $text);

        $row++;
        $companyWorkSheet->mergeCells("B$row:I$row");
        if($company->is_split) {
            $companyWorkSheet->mergeCells("B$row:J$row");
        }
        $companyWorkSheet->getRowDimension($row)->setRowHeight(30);
        $companyWorkSheet->getStyle("B$row:I$row")->getAlignment()->setWrapText(true);
        $text = "Настоящий Акт составлен в 2 (двух) экземплярах, один из которых находится у Исполнителя, второй – у Заказчика.";
        $companyWorkSheet->setCellValue("B$row", $text);

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->mergeCells("G$row:I$row");
        if($company->is_split) {
            $companyWorkSheet->mergeCells("G$row:J$row");
        }
        if ($this->showCompany) {
            $companyWorkSheet->setCellValue("B$row", "Работу сдал");
            $companyWorkSheet->setCellValue("G$row", "Работу принял");
        } else {
            $companyWorkSheet->setCellValue("B$row", "Работу принял");
            $companyWorkSheet->setCellValue("G$row", "Работу сдал");
        }

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->mergeCells("G$row:I$row");
        if($company->is_split) {
            $companyWorkSheet->mergeCells("G$row:J$row");
        }
        if ($this->showCompany) {
            $companyWorkSheet->setCellValue("B$row", "Исполнитель");
            $companyWorkSheet->setCellValue("G$row", "Заказчик");
        } else {
            $companyWorkSheet->setCellValue("B$row", "Заказчик");
            $companyWorkSheet->setCellValue("G$row", "Исполнитель");
        }

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->mergeCells("G$row:I$row");
        if($company->is_split) {
            $companyWorkSheet->mergeCells("G$row:J$row");
        }
        if ($this->showCompany) {
            $companyWorkSheet->setCellValue("B$row", "____________Мосесян Г.А.");
            $companyWorkSheet->setCellValue("G$row", "____________$company->contact");
        } else {
            $companyWorkSheet->setCellValue("B$row", "____________Мосесян Г.А.");
            $companyWorkSheet->setCellValue("G$row", "____________$company->contact");
        }

        $row++; $row++;
        $companyWorkSheet->setCellValue("C$row", "М.П.");
        $companyWorkSheet->setCellValue("G$row", "М.П.");

        //saving document
        $path = "acts/" . date('m-Y', $this->time);
        if (!is_dir($path)) {
            mkdir($path, 0755, 1);
        }
        if ($this->companyType == Company::SERVICE_TYPE) {
            $first = $dataList[0];
            $filename = "Акт $company->name от " . date('d-m-Y', strtotime($first->service_date)) . ".xls";
        } else {
            $filename = "Акт $company->name от " . date('m-Y', $this->time) . ".xls";
        }
        $fullFilename = str_replace(' ', '_', "$path/" . str_replace('"', '', "$filename"));
        $objWriter->save($fullFilename);
        if ($zip) $zip->addFile($fullFilename, iconv('utf-8', 'cp866', $filename));

        if (!$this->showCompany) {
            return;
        }
        ///////////// check
        $this->objPHPExcel = new PHPExcel();
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');

        // Creating a workbook
        $this->objPHPExcel->getProperties()->setCreator('Mtransservice');
        $this->objPHPExcel->getProperties()->setTitle('Счет');
        $this->objPHPExcel->getProperties()->setSubject('Счет');
        $this->objPHPExcel->getProperties()->setDescription('');
        $this->objPHPExcel->getProperties()->setCategory('');
        $this->objPHPExcel->removeSheetByIndex(0);

        //adding worksheet
        $companyWorkSheet = new PHPExcel_Worksheet($this->objPHPExcel, 'Счет');
        $this->objPHPExcel->addSheet($companyWorkSheet);

        $companyWorkSheet->getRowDimension(1)->setRowHeight(100);
        $companyWorkSheet->getColumnDimension('A')->setWidth(5);
        $companyWorkSheet->getColumnDimension('B')->setWidth(20);
        $companyWorkSheet->getColumnDimension('C')->setWidth(20);
        $companyWorkSheet->getColumnDimension('D')->setWidth(10);
        $companyWorkSheet->getColumnDimension('E')->setWidth(30);
        $companyWorkSheet->getDefaultRowDimension()->setRowHeight(20);

        //headers
        $monthName = StringNum::getMonthName($this->time);
        $date = date_create(date('Y-m-d', $this->time));
        date_add($date, date_interval_create_from_date_string("1 month"));

        $companyWorkSheet->mergeCells('B2:E2');
        $companyWorkSheet->setCellValue('B2', "ООО «Международный Транспортный Сервис»");

        $companyWorkSheet->mergeCells('B3:E3');
        $companyWorkSheet->setCellValue('B3', "Адрес: 394065, г. Воронеж, ул. Героев Сибиряков, д. 24, оф. 116");

        $companyWorkSheet->getStyle("B5")->applyFromArray(array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    ),
                ),
            )
        );
        $companyWorkSheet->setCellValue('B5', 'ИНН 366 510 0480');

        $companyWorkSheet->getStyle("C5")->applyFromArray(array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    ),
                ),
            )
        );
        $companyWorkSheet->setCellValue('C5', 'КПП 366 501 001');

        $companyWorkSheet->mergeCells('B6:C6');
        $companyWorkSheet->getStyle("B6:C6")->getAlignment()->setWrapText(true);
        $companyWorkSheet->getStyle("B6:C6")->applyFromArray(array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    ),
                ),
            )
        );
        $companyWorkSheet->getRowDimension(6)->setRowHeight(40);
        $companyWorkSheet->setCellValue('B6', 'Получатель:ООО«Международный Транспортный Сервис»');

        $companyWorkSheet->mergeCells('D5:D6');
        $companyWorkSheet->getStyle("D5:D6")->applyFromArray(array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    ),
                ),
                'alignment' => array(
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            )
        );
        $companyWorkSheet->setCellValue('D5', 'Сч.№');

        $companyWorkSheet->mergeCells('E5:E6');
        $companyWorkSheet->getStyle("E5:E6")->applyFromArray(array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    ),
                ),
                'alignment' => array(
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            )
        );
        $companyWorkSheet->getStyle('E5:E6')
            ->getNumberFormat()
            ->setFormatCode(
                PHPExcel_Style_NumberFormat::FORMAT_TEXT
            );
        $companyWorkSheet->setCellValue('E5', ' 40702810913000016607');

        $companyWorkSheet->mergeCells('B7:C8');
        $companyWorkSheet->getStyle("B7:C8")->getAlignment()->setWrapText(true);
        $companyWorkSheet->getStyle("B7:C8")->applyFromArray(array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    ),
                ),
            )
        );
        $companyWorkSheet->setCellValue('B7', 'Банк получателя: Центрально-Черноземный Банк ОАО «Сбербанк России»  г. Воронеж');

        $companyWorkSheet->getStyle("D7")->applyFromArray(array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    ),
                ),
            )
        );
        $companyWorkSheet->setCellValue('D7', 'БИК');

        $companyWorkSheet->getStyle("E7")->applyFromArray(array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    ),
                ),
            )
        );
        $companyWorkSheet->getStyle('E7')
            ->getNumberFormat()
            ->setFormatCode(
                PHPExcel_Style_NumberFormat::FORMAT_TEXT
            );
        $companyWorkSheet->setCellValue('E7', ' 042007681');

        $companyWorkSheet->getStyle("D8")->applyFromArray(array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    ),
                ),
            )
        );
        $companyWorkSheet->setCellValue('D8', 'К/сч.№');

        $companyWorkSheet->getStyle("E8")->applyFromArray(array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    ),
                ),
            )
        );
        $companyWorkSheet->getStyle('E8')
            ->getNumberFormat()
            ->setFormatCode(
                PHPExcel_Style_NumberFormat::FORMAT_TEXT
            );
        $companyWorkSheet->setCellValue('E8', ' 30101810600000000681');

        $row = 9;
        $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->getStyle("B$row:E$row")->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            )
        );
        if ($this->companyType != Company::CARWASH_TYPE) {
            $first = $dataList[0];
            $text = "СЧЕТ б/н от " . date("d ", strtotime($first->service_date)) . ' ' . $monthName[1] . date(' Y', $this->time);
        } else {
            $text = "СЧЕТ б/н от " . date("t", $this->time) . ' ' . $monthName[1] . date(' Y', $this->time);
        }
        $companyWorkSheet->setCellValue("B$row", $text);

        $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->getStyle("B$row:E$row")->applyFromArray(array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            )
        );
        $text = 'За услуги, оказанные в ' . $monthName[2] . date(' Y');
        $companyWorkSheet->setCellValue("B$row", $text);

        $row++;
        $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $text = "Плательщик: $company->name";
        $companyWorkSheet->setCellValue("B$row", $text);

        $row++;
        $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->getRowDimension($row)->setRowHeight(40);
        $companyWorkSheet->getStyle("B$row:E$row")->getAlignment()->setWrapText(true);
        $text = "Всего наименований " .count($dataList) . ", на сумму $total (" . StringNum::num2str($total) . "). НДС нет.";
        $companyWorkSheet->setCellValue("B$row", $text);

        $row++;
        $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->setCellValue("B$row", 'Мосесян Г.А.');

        //saving document
        $path = "acts/" . date('m-Y', $this->time);
        if (!is_dir($path)) {
            mkdir($path, 0755, 1);
        }
        if ($this->companyType == Company::SERVICE_TYPE) {
            $first = $dataList[0];
            $filename = "Счет $company->name от " . date('d-m-Y', strtotime($first->service_date)) . ".xls";
        } else {
            $filename = "Счет $company->name от " . date('m-Y', $this->time) . ".xls";
        }
        $fullFilename = str_replace(' ', '_', "$path/" . str_replace('"', '', "$filename"));
        $objWriter->save($fullFilename);
        if ($zip) $zip->addFile($fullFilename, iconv('utf-8', 'cp866', $filename));
    }

    private function sendHeaders() {
        if ($this->headersSent === false) {
            $this->headersSent = true;

            header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
            header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
            header ( "Cache-Control: no-cache, must-revalidate" );
            header ( "Pragma: no-cache" );
            header ( "Content-type: application/vnd.ms-excel" );
            header ( "Content-Disposition: attachment; filename=" . date('Y-m',$this->time) . "-$this->filename" );
        }
    }

    public function isExportRequest() {
        return Yii::app()->request->getParam($this->exportParam, false);
    }

    public function renderExportGridButton($gridId, $label = 'Export', $htmlOptions = array()) {
        if (!isset($htmlOptions['id'])) {
            $htmlOptions['id'] = $this->buttonId;
        }
        echo CHtml::link($label, '#', $htmlOptions);
        Yii::app()->clientScript->registerScript('exportgrid'.$htmlOptions['id'], "$('#" . $htmlOptions['id'] . "').on('click',function() { 
            var downloadUrl = window.location.href;
            downloadUrl+=((downloadUrl.indexOf('?')==-1)?'?':'&');
            downloadUrl+='{$this->exportParam}=1';
            window.open( downloadUrl ,'_blank');
            return false;
        });");
    }

}
