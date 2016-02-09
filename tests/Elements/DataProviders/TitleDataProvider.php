<?php namespace Test\Elements\DataProviders;


/**
 * Class TitleDataProvider
 * @package Tests\Elements\DataProviders
 */
trait TitleDataProvider
{
    use TestObjectCreator;

    /**
     * Generate test data without language.
     * @return array
     */
    public function getDataWithOutLanguage()
    {
        $testObject = $this->createTestObjectWith(['text' => 'test', 'xml_lang' => '']);

        return ['test' => ['title' => [$testObject], 'lang' => '']];
    }

    /**
     * Generate test data with all Fields.
     * @return array
     */
    protected function getDataWithAllFields()
    {
        $testObject = $this->createTestObjectWith(['text' => 'test', 'xml_lang' => 'randomTestLanguageCode']);

        return ['test' => ['title' => [$testObject], 'lang' => 'randomTestLanguage']];
    }

    /**
     * Generate Test data with out any fields.
     * @return array
     */
    protected function getDataWithoutAnyFields()
    {
        $testObject = $this->createTestObjectWith(['text' => '', 'xml_lang' => '']);

        return ['test' => ['title' => [$testObject], 'lang' => '']];
    }

    /**
     * Generate test data with multiple narratives.
     * @return array
     */
    protected function getDataWithMultipleNarratives()
    {
        $testObject1 = $this->createTestObjectWith(['text' => 'test1', 'xml_lang' => 'randomTestLanguageCode1']);
        $testObject2 = $this->createTestObjectWith(['text' => 'test2', 'xml_lang' => 'randomTestLanguageCode2']);
        $testObject3 = $this->createTestObjectWith(['text' => 'test3', 'xml_lang' => 'randomTestLanguageCode3']);

        return ['test' => ['title' => [$testObject1, $testObject2, $testObject3], 'lang' => ['a', 'b', 'c']]];
    }
}
