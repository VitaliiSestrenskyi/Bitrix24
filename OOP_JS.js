if( typeof (CrmValidator) === "undefined")
{
    CrmValidator = function ()
    {
        this.element = "";
        this.validationTypes = [];
        this.errorMessages = {};
    };
    CrmValidator.prototype =
    {
        initialize: function (element, validationTypes, errorMessages)
        {
            this._this = this;
            this._element = element;
            this._validationTypes = validationTypes;
            this._errorMessages = errorMessages;
        },
    };
}

if( typeof (CrmValidatorInput) === "undefined")
{
    CrmValidatorInput = function ()
    {

    };

    //Extend + Save constructor
    CrmValidatorInput.prototype = Object.create(CrmValidator.prototype);
    CrmValidatorInput.prototype.constructor = CrmValidatorInput;

    CrmValidatorInput.prototype.validate = function()
    {
        console.log( this );
    };

    CrmValidatorInput.create = function(element, validationTypes, errorMessages)
    {
        var self = new CrmValidatorInput();
        self.initialize(element, validationTypes, errorMessages);
        return self;
    };
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// ECMAScript 6 /////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


class CrmValidator
{
    constructor()
    {
        this.element = null;
        this.validationTypes = {};
        this.errorMessages = {};
        this.result = [];
    }

    initialize(element, validationTypes, errorMessages)
    {
        this.setElement(element);
        this.setValidationTypes(validationTypes);
        this.setErrorMessages(errorMessages);
    }

    setResult(res)
    {
        this.result.push(res);
    }
    setElement(element)
    {
        this.element = element;
    }
    setValidationTypes(validationTypes)
    {
        this.validationTypes = validationTypes;
    }
    setErrorMessages(errorMessages)
    {
        this.errorMessages = errorMessages;
    }
    getResult()
    {
        return this.result;
    }
    getElement()
    {
        return this.element;
    }
    getValidationTypes()
    {
        return this.validationTypes;
    }
    getErrorMessages()
    {
        return this.errorMessages;
    }


    validate()
    {
    }
    getFormattedResult()
    {
    }

}



class CrmValidatorInput extends CrmValidator
{
    validate()
    {
        let _validationTypes = this.getValidationTypes();
        for(let i in _validationTypes)
        {
            switch (_validationTypes[i])
            {
                case 'isString':

                    break;
                case 'isNotEmptyString':
                    if (typeof (this.getElementValue()) === "undefined" || this.getElementValue().length === 0)
                    {
                        this.setResult( this.getErrorMessages()[i] );
                    }
                    break;
                case 'isBoolean':

                    break;
                case 'isNumber':
                    if( isNaN( Number(this.getElementValue()) ) ||  Number(this.getElementValue()) === 0 )
                    {
                        this.setResult( this.getErrorMessages()[i] );
                    }
                    break;
                case 'isMinusNumber':
                    if (this.getElementValue().match(/^-\d+$/))
                    {
                        this.setResult( this.getErrorMessages()[i] );
                    }
                    break;
                case 'isInteger':
                    if(Number.isInteger( Number(this.getElementValue()) ) === false)
                    {
                        this.setResult( this.getErrorMessages()[i] );
                    }
                    break;
                case 'isFunction':

                    break;
                case 'isDomNode':

                    break;
                case 'isElementNode':

                    break;
                case 'isArray':

                    break;
                case 'isDate':

                    break;
                default:

                    break;
            }
        }
    }

    getElementValue()
    {
        return BX.util.trim( this.getElement().value );
    }

    getFormattedResult()
    {
        return this.getResult().join(", ");
    }

    static create(element, validationTypes, errorMessages)
    {
        let self = new CrmValidatorInput();
        self.initialize(element, validationTypes, errorMessages);
        return self;
    }
}













