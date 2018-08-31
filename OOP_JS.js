 function Animal(name)
        {
            this.name = name;
            this.canWalk = "привет от животного";
        };

        Animal.prototype.test = function (changeCanWalk)
        {
            this.canWalk = changeCanWalk;
            return this.canWalk;
        };

        Animal.prototype.simpleMethod = function () {
            return 'простой стат метод';
        };

        function Rabbit(name)
        {
            Rabbit.superclass.constructor.call(this, name);
            this.name = name;
        };

        function extend(Child, Parent)
        {
            var F = function() { };
            F.prototype = Parent.prototype;
            Child.prototype = new F();
            Child.prototype.constructor = Child;
            Child.superclass = Parent.prototype;
        };

        extend(Rabbit, Animal);
        //BX.extend(Rabbit, Animal);

        var rabbit = new Rabbit("тест кролика");

        console.log(rabbit.canWalk);
        console.log( rabbit.test("NEWWWWW") );
        console.log( rabbit.simpleMethod() );
        console.log( rabbit.__proto__ );
