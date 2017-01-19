<?php

//Наблюдаемый
interface IObservable
{
  public function addObserver( IObserver $objObserver, $strEventType );
  public function fireEvent( $strEventType );
}

//Наблюдатель
interface IObserver
{
  public function notify( IObservable $objSource, $objArguments );
}










//e-mail валидатор, который описывает интерфейс IObservable и определяет два типа событий
class EmailValidator implements IObservable
{
  const EVENT_EMAIL_VALID = 1;
  const EVENT_EMAIL_INVALID = 2;
 
  protected $strEmailAddress;
 
  protected $aryObserversArray;
 
  public function __construct( $strEmailAddress )
  {
    $this->strEmailAddress = $strEmailAddress;
    $this->aryObserversArray = array( array() );
  }
 
  public function setEmailAddress( $strEmailAddress )
  {
    $this->strEmailAddress = $strEmailAddress;
  }
 
  public function getEmailAddress()
  {
    return $this->strEmailAddress;
  }
 
  public function validate()
  {
    if( preg_match( '/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@'.
'[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/',
        $this->strEmailAddress ) )
    {
      $this->fireEvent( EmailValidator::EVENT_EMAIL_VALID );
    }
    else
    {
      $this->fireEvent( EmailValidator::EVENT_EMAIL_INVALID );
    }
  }
 
  public function addObserver( IObserver $objObserver, $strEventType )
  {
    $this->aryObserversArray[$strEventType][] = $objObserver;
  }
 
  public function fireEvent( $strEventType )
  {
    if( is_array( $this->aryObserversArray[$strEventType] ) )
    {
      foreach ( $this->aryObserversArray[$strEventType] as $objObserver )
      {
        $objObserver->notify( $this, $strEventType );
      }
    }
  }
}












//Наблюдатель 1
class ErrorLogger implements IObserver
{
  public function notify( IObservable $objSource, $strEventType )
  {
    if( $strEventType == EmailValidator::EVENT_EMAIL_INVALID && $objSource instanceof EmailValidator )
    {
      printf( 'Ошибка: %s невалидный email адрес.',
             $objSource->getEmailAddress() );
    }
  }
}


//Наблюдатель 2
class DatabaseWriter implements IObserver
{
  public function notify( IObservable $objSource, $strEventType )
  {
    if( $strEventType == EmailValidator::EVENT_EMAIL_VALID && $objSource instanceof EmailValidator )
    {
      printf( 'Email адрес %s валидный и был записан в базу данных.',
             $objSource->getEmailAddress() );
    }
  }
}




//Реализация
$objValidator = new EmailValidator( 'valid@email.com' );
$objValidator->addObserver( new ErrorLogger(), EmailValidator::EVENT_EMAIL_INVALID );
$objValidator->addObserver( new DatabaseWriter(), EmailValidator::EVENT_EMAIL_VALID );
$objValidator->validate();
 
$objValidator->setEmailAddress( 'not_a_valid_address' );
$objValidator->validate();













