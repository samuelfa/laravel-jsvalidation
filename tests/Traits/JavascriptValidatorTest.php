<?php


namespace Proengsoft\JsValidation\Test\Traits;

use Proengsoft\JsValidation\Validator;


class JavascriptValidatorTest extends \PHPUnit_Framework_TestCase {

    public $validator;
    public $translator;


    public function setUp()
    {

        $this->translator = \Mockery::instanceMock('Symfony\Component\Translation\TranslatorInterface')
            ->shouldReceive('trans')
            ->getMock();

        $messages = [
            'name.required'=>'name required',
        ];
        $data = [];
        $rules = ['name'=>'required', 'novalidate'=>'no_js_validation'];
        $customAttributes = [];

        $this->validator= new Validator($this->translator, $data, $rules,$messages ,$customAttributes);
    }


    public function testJsValidationEnabled()
    {
        $data=$this->validator->jsValidationEnabled('name');
        $this->assertTrue($data);
    }


    public function testJsValidationDisabled()
    {
        $data=$this->validator->jsValidationEnabled('novalidate');
        $this->assertFalse($data);
    }


    public function testValidateNoJsValidation()
    {
        $this->assertTrue($this->validator->validateNoJsValidation());
    }


    public function testJs() {

        $expected=array(
            'rules' => array('name'=>['laravelRequired'=>[]]),
            'messages' =>  array('name'=>['laravelRequired'=>'name required']),
        );

        $data=$this->validator->js();
        $this->assertEquals($expected,$data);

    }


    public function testNotImplementedRules()
    {
        $rule=['name'=>'not_implemented_rule'];
        $message=['name.not_implemented_rule'=>'not implemented rule'];
        $expected=array(
            'rules' => array(),
            'messages'=>array()
        );

        $validator=new Validator($this->translator, [], $rule,$message);
        $data=$validator->js();

        $this->assertEquals($expected,$data);

    }


    public function testJsRuleConfirmed(){

        $rule=['name'=>'confirmed'];
        $message=['name.confirmed'=>'name confirmed'];
        $expected=array(
            'rules' => array(
                'name_confirmation'=> ['laravelConfirmed'=>array('name')]
            ),
            'messages'=>array(
                'name_confirmation'=> ['laravelConfirmed'=>'name confirmed']
            )
        );

        $validator=new Validator($this->translator, [], $rule,$message);
        $data=$validator->js();

        $this->assertEquals($expected,$data);

    }


    public function testAfter(){

        $mayDay="May 4, 1886";
        $rule=['name'=>'after:"'.$mayDay.'"'];
        $message=['name.after'=>'May Day'];
        $expected=array(
            'rules' => array(
                'name'=> ['laravelAfter'=>array(strtotime($mayDay))]
            ),
            'messages'=>array(
                'name'=> ['laravelAfter'=>'May Day']
            )
        );

        $validator=new Validator($this->translator, [], $rule,$message);
        $data=$validator->js();

        $this->assertEquals($expected,$data);

    }


    public function testBefore(){

        $mayDay="May 4, 1886";
        $rule=['name'=>'before:"'.$mayDay.'"'];
        $message=['name.before'=>'May Day'];
        $expected=array(
            'rules' => array(
                'name'=> ['laravelBefore'=>array(strtotime($mayDay))]
            ),
            'messages'=>array(
                'name'=> ['laravelBefore'=>'May Day']
            )
        );

        $validator=new Validator($this->translator, [], $rule,$message);
        $data=$validator->js();

        $this->assertEquals($expected,$data);

    }


}
