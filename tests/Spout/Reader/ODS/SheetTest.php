<?php

namespace Box\Spout\Reader\ODS;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\TestUsingResource;

/**
 * Class SheetTest
 *
 * @package Box\Spout\Reader\ODS
 */
class SheetTest extends \PHPUnit_Framework_TestCase
{
    use TestUsingResource;

    /**
     * @return void
     */
    public function testReaderShouldReturnCorrectSheetInfos()
    {
        // NOTE: This spreadsheet has its second tab defined as active
        $sheets = $this->openFileAndReturnSheets('two_sheets_with_custom_names.ods');

        $this->assertEquals('Sheet First', $sheets[0]->getName());
        $this->assertEquals(0, $sheets[0]->getIndex());
        $this->assertFalse($sheets[0]->isActive());

        $this->assertEquals('Sheet Last', $sheets[1]->getName());
        $this->assertEquals(1, $sheets[1]->getIndex());
        $this->assertTrue($sheets[1]->isActive());
    }

    /**
     * @return void
     */
    public function testReaderShouldDefineFirstSheetAsActiveByDefault()
    {
        // NOTE: This spreadsheet has no information about the active sheet
        $sheets = $this->openFileAndReturnSheets('two_sheets_with_no_settings_xml_file.ods');

        $this->assertTrue($sheets[0]->isActive());
        $this->assertFalse($sheets[1]->isActive());
    }

    /**
     * @param string $fileName
     * @return Sheet[]
     */
    private function openFileAndReturnSheets($fileName)
    {
        $resourcePath = $this->getResourcePath($fileName);
        $reader = ReaderFactory::create(Type::ODS);
        $reader->open($resourcePath);

        $sheets = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $sheets[] = $sheet;
        }

        $reader->close();

        return $sheets;
    }

    /**
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    public function testSheetIteratorCount()
    {
        $resourcePath = $this->getResourcePath('two_sheets_with_custom_names.ods');

        /** @var \Box\Spout\Reader\XLSX\Reader $reader */
        $reader = ReaderFactory::create(Type::ODS);
        $reader->setShouldFormatDates(false);
        $reader->setShouldPreserveEmptyRows(false);
        $reader->open($resourcePath);

        $iterator = $reader->getSheetIterator();

        $this->assertEquals(2,count($iterator));
    }

    public function testRowIteratorCount()
    {
        $sheets = $this->openFileAndReturnSheets('row_counts_0_1_1000.ods');

        $counts =[0,1,1000];

        foreach ($sheets as $key=>$sheet)
        {
            $rowIterator = $sheet->getRowIterator();

            $this->assertEquals($counts[$key],count($rowIterator));
        }
    }
}
