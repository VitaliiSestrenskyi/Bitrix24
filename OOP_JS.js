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
