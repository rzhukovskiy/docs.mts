<?php
class ExportableGridBehavior extends CBehavior
{
    public $buttonId = 'export-button';		//Id used on the export link
    public $exportParam = 'exportCSV';		//Param to be appended on url to fire an export
    public $filename = 'export.xls';		//Filename suggested to user when downloading
    /** @var PHPExcel $objPHPExcel */
    public $objPHPExcel = null;
    private $headersSent = false;
    private $time = null;
    

    /**
     * @param Act $actModel
     */
    public function exportCSV($actModel)
    {
        if ($this->isExportRequest()) {
            $this->time = $actModel->service_date;
            //$this->sendHeaders();

            spl_autoload_unregister(array('YiiBase','autoload'));
            Yii::import("ext.PHPExcel.Classes.PHPExcel", true);
            spl_autoload_register(array('YiiBase','autoload'));

            $zip = new ZipArchive();
            $filename = "acts/" . date('m-Y', $this->time) . "/$actModel->companyType.zip";

            if ($zip->open($filename, ZipArchive::OVERWRITE) !== TRUE) {
                $zip = null;
            }
            /** @var Company $company */
            foreach(Company::model()->findAll(array(
                'condition' => 'type = :type',
                'params' => array(':type' => $actModel->companyType),
                'order' => 'type DESC'
            )) as $company) {
                $actModel->company_id = $company->id;
                $this->fillCompanyAct($company, $zip);
            }
            if ($zip) $zip->close();
        }
    }

    /**
     * @param Company $company
     */
    private function fillCompanyAct($company, &$zip)
    {
        if ($company->type == Company::CARWASH_TYPE) {
            $actList = Act::model()->findAll(array(
                'order' => 'service_date',
                'condition' => 'company_id = :company_id AND date_format(service_date, "%Y-%m") = :date',
                'params' => array(
                    ':date' => date('Y-m', $this->time),
                    ':company_id' => $company->id,
                )
            ));

            if (!$actList) {
                return;
            }

            $this->generateCarwashAct($company, $actList, $zip);
        } else {
            $actList = Act::model()->with('card')->findAll(array(
                'order' => 'service_date',
                'condition' => 'card.company_id = :company_id AND date_format(service_date, "%Y-%m") = :date',
                'params' => array(
                    ':date' => date('Y-m', $this->time),
                    ':company_id' => $company->id,
                )
            ));
            if (!$actList) {
                return;
            }

            $this->generateCompanyAct($company, $actList, $zip);
        }
    }

    /**
     * @param Company $company
     * @param Act $actList
     * @param ZipArchive $zip
     */
    private function generateCompanyAct($company, $actList, &$zip)
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
        $companyWorkSheet->getRowDimension(1)->setRowHeight(1);
        $companyWorkSheet->getRowDimension(10)->setRowHeight(100);
        $companyWorkSheet->getColumnDimension('A')->setWidth(5);
        $companyWorkSheet->getColumnDimension('B')->setWidth(5);
        $companyWorkSheet->getColumnDimension('C')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('D')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('E')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('F')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('G')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('H')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('I')->setAutoSize(true);
        $companyWorkSheet->getDefaultRowDimension()->setRowHeight(20);

        //headers;
        $monthName = StringNum::getMonthName($this->time);
        $date = date_create(date('Y-m-d', $this->time));
        date_add($date, date_interval_create_from_date_string("1 month"));

        $companyWorkSheet->getStyle('B2:I4')->applyFromArray(array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        ));
        $companyWorkSheet->mergeCells('B2:I2');
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
        $companyWorkSheet->setCellValue('H5', date("t ", $this->time) . $monthName[1] . date(' Y', $this->time));

        $companyWorkSheet->mergeCells('B8:I8');
        $companyWorkSheet->setCellValue('B8', "Исполнитель: ООО «Международный Транспортный Сервис»");
        $companyWorkSheet->mergeCells('B7:I7');
        $companyWorkSheet->setCellValue('B7', "Заказчик: $company->name");

        $companyWorkSheet->mergeCells('B10:I10');
        $companyWorkSheet->getStyle('B10:I10')->getAlignment()->setWrapText(true);
        $companyWorkSheet->getStyle('B10:I10')->applyFromArray(array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
            )
        ));
        $companyWorkSheet->setCellValue('B10', $company->act_header);

        $headers = array('№', 'Число', '№ Карты', 'Марка ТС', 'Госномер', 'Вид услуги', 'Стоимость', '№ Чека');
        $companyWorkSheet->fromArray($headers, null, 'B12');


        //main values
        /** @var Act $act */
        $row = 12;
        $num = 0;
        $total = 0;
        foreach ($actList as $act) {
            $row++;
            $num++;
            $column = 1;
            $date = new DateTime($act->service_date);
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $num);
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $date->format('j'));
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $act->card->num);
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $act->mark->name);
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $act->number);
            if ($company->type == Company::CARWASH_TYPE) {
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, Act::$carwashList[$act->service]);
            } else {
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, Act::$carwashList[$act->company_service]);
            }
            if ($company->type == Company::CARWASH_TYPE) {
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $act->expense);
                $total += $act->expense;
            } else {
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $act->income);
                $total += $act->income;
            }
            $companyWorkSheet->getCellByColumnAndRow($column, $row)
                ->getStyle()
                ->getNumberFormat()
                ->setFormatCode(
                    PHPExcel_Style_NumberFormat::FORMAT_TEXT
                );
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, ' ' . $act->check);
        }

        $companyWorkSheet->getStyle('B12:I12')->applyFromArray(array(
                'font' => array(
                    'bold' => true,
                    'color' => array('argb' => 'FF006699'),
                ),
            )
        );
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

        //footer
        $row++;
        $companyWorkSheet->setCellValue("H$row", "ВСЕГО: $total");

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:I$row");
        $companyWorkSheet->getRowDimension($row)->setRowHeight(30);
        $companyWorkSheet->getStyle("B$row:I$row")->getAlignment()->setWrapText(true);
        $text = "Общая стоимость выполненных услуг составляет: $total (" . StringNum::num2str($total) . ") рублей. НДС нет.";
        $companyWorkSheet->setCellValue("B$row", $text);

        $row++;
        $companyWorkSheet->mergeCells("B$row:I$row");
        $companyWorkSheet->getRowDimension($row)->setRowHeight(30);
        $companyWorkSheet->getStyle("B$row:I$row")->getAlignment()->setWrapText(true);
        $text = "Настоящий Акт составлен в 2 (двух) экземплярах, один из которых находится у Исполнителя, второй – у Заказчика.";
        $companyWorkSheet->setCellValue("B$row", $text);

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->setCellValue("B$row", "Работу сдал");
        $companyWorkSheet->mergeCells("H$row:I$row");
        $companyWorkSheet->setCellValue("H$row", "Работу принял");

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->setCellValue("B$row", "Исполнитель");
        $companyWorkSheet->mergeCells("H$row:I$row");
        $companyWorkSheet->setCellValue("H$row", "Заказчик");

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->setCellValue("B$row", "__________ Мосесян Г.А.");
        $companyWorkSheet->mergeCells("H$row:I$row");
        $companyWorkSheet->setCellValue("H$row", "__________$company->contact");

        $row++; $row++;
        $companyWorkSheet->setCellValue("C$row", "М.П.");
        $companyWorkSheet->setCellValue("H$row", "М.П.");

        //saving document
        $path = "acts/" . date('m-Y', $this->time);
        if (!is_dir($path)) {
            mkdir($path, 0755, 1);
        }
        $filename = "Акт $company->name от " . date('m-Y', $this->time) . ".xls";
        $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
        $objWriter->save($fullFilename);
        if ($zip) $zip->addFile($fullFilename, iconv('utf-8', 'cp866', $filename));

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
        $text = "СЧЕТ б/н от " . date("t", $this->time) . ' ' . $monthName[1] . date(' Y', $this->time);
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
        $text = "Всего наименований " .count($actList) . ", на сумму $total (" . StringNum::num2str($total) . "). НДС нет.";
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
        $filename = "Счет $company->name от " . date('m-Y', $this->time) . ".xls";
        $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
        $objWriter->save($fullFilename);
        if ($zip) $zip->addFile($fullFilename, iconv('utf-8', 'cp866', $filename));
    }

    /**
     * @param Company $company
     * @param Act $actList
     * @param ZipArchive $zip
     */
    private function generateCarwashAct($company, $actList, &$zip)
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
        $companyWorkSheet = new PHPExcel_Worksheet($this->objPHPExcel, 'Акт');
        $this->objPHPExcel->addSheet($companyWorkSheet);

        $companyWorkSheet->getPageMargins()->setTop(2);
        $companyWorkSheet->getRowDimension(1)->setRowHeight(1);
        $companyWorkSheet->getRowDimension(10)->setRowHeight(100);
        $companyWorkSheet->getColumnDimension('A')->setWidth(5);
        $companyWorkSheet->getColumnDimension('B')->setWidth(5);
        $companyWorkSheet->getColumnDimension('C')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('D')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('E')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('F')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('G')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('H')->setAutoSize(true);
        $companyWorkSheet->getColumnDimension('I')->setAutoSize(true);
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
        $companyWorkSheet->setCellValue('H5', date('d ') . $currentMonthName[1] . date(' Y'));

        $companyWorkSheet->mergeCells('B7:I7');
        $companyWorkSheet->setCellValue('B7', "Исполнитель: $company->name");
        $companyWorkSheet->mergeCells('B8:I8');
        $companyWorkSheet->setCellValue('B8', "Заказчик: ООО «Международный Транспортный Сервис»");

        $companyWorkSheet->mergeCells('B10:I10');
        $companyWorkSheet->getStyle('B10:I10')->getAlignment()->setWrapText(true);
        $companyWorkSheet->getStyle('B10:I10')->applyFromArray(array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
            )
        ));
        $companyWorkSheet->setCellValue('B10', $company->act_header);

        $headers = array('№', 'Число', '№ Карты', 'Марка ТС', 'Госномер', 'Вид услуги', 'Стоимость', '№ Чека');
        $companyWorkSheet->fromArray($headers, null, 'B12');


        //main values
        /** @var Act $act */
        $row = 12;
        $num = 0;
        $total = 0;
        foreach ($actList as $act) {
            $row++;
            $num++;
            $column = 1;
            $date = new DateTime($act->service_date);
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $num);
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $date->format('j'));
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $act->card->num);
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $act->mark->name);
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $act->number);
            if ($company->type == Company::CARWASH_TYPE) {
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, Act::$carwashList[$act->service]);
            } else {
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, Act::$carwashList[$act->company_service]);
            }
            if ($company->type == Company::CARWASH_TYPE) {
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $act->expense);
                $total += $act->expense;
            } else {
                $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, $act->income);
                $total += $act->income;
            }
            $companyWorkSheet->getCellByColumnAndRow($column, $row)
                ->getStyle()
                ->getNumberFormat()
                ->setFormatCode(
                    PHPExcel_Style_NumberFormat::FORMAT_TEXT
                );
            $companyWorkSheet->setCellValueByColumnAndRow($column++, $row, ' ' . $act->check);
        }

        $companyWorkSheet->getStyle('B12:I12')->applyFromArray(array(
                'font' => array(
                    'bold' => true,
                    'color' => array('argb' => 'FF006699'),
                ),
            )
        );
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

        //footer
        $row++;
        $companyWorkSheet->setCellValue("H$row", "ВСЕГО: $total");

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:I$row");
        $companyWorkSheet->getRowDimension($row)->setRowHeight(30);
        $companyWorkSheet->getStyle("B$row:I$row")->getAlignment()->setWrapText(true);
        $text = "Общая стоимость выполненных услуг составляет: $total (" . StringNum::num2str($total) . ") рублей. НДС нет.";
        $companyWorkSheet->setCellValue("B$row", $text);

        $row++;
        $companyWorkSheet->mergeCells("B$row:I$row");
        $companyWorkSheet->getRowDimension($row)->setRowHeight(30);
        $companyWorkSheet->getStyle("B$row:I$row")->getAlignment()->setWrapText(true);
        $text = "Настоящий Акт составлен в 2 (двух) экземплярах, один из которых находится у Исполнителя, второй – у Заказчика.";
        $companyWorkSheet->setCellValue("B$row", $text);

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->setCellValue("B$row", "Работу принял");
        $companyWorkSheet->mergeCells("H$row:I$row");
        $companyWorkSheet->setCellValue("H$row", "Работу сдал");

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->setCellValue("B$row", "Заказчик");
        $companyWorkSheet->mergeCells("H$row:I$row");
        $companyWorkSheet->setCellValue("H$row", "Исполнитель");

        $row++; $row++;
        $companyWorkSheet->mergeCells("B$row:E$row");
        $companyWorkSheet->setCellValue("B$row", "________Мосесян Г.А.");
        $companyWorkSheet->mergeCells("H$row:I$row");
        $companyWorkSheet->setCellValue("H$row", "________$company->contact");

        $row++; $row++;
        $companyWorkSheet->setCellValue("C$row", "М.П.");
        $companyWorkSheet->setCellValue("H$row", "М.П.");

        //saving document
        $path = "acts/" . date('m-Y', $this->time);
        if (!is_dir($path)) {
            mkdir($path, 0755, 1);
        }
        $filename = "Акт $company->name от " . date('m-Y', $this->time) . ".xls";
        $fullFilename = str_replace(' ', '_', str_replace('"', '', "$path/$filename"));
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
