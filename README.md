# populate

<h1>How to use Populate class</h1>
<div>First you need to setup the class with Populate::setup(dbname,user,password)</div>
<div>Then make a maker like this maker = Populate::maker(column_name)</div>
<div>Finaly maker->populate(number_of_row, [data])</div>
<div>data should be an array of corresponding type like :</div>
<div style='color:red'>
[
    <div>['type' => 'string', 'len' => number],</div>         
    <div>['type' => 'number', 'min' => number, 'max' => number],</div>
    <div>['type' => 'fk', 'table' => string],</div>     
]
</div>  

<div>Every time need a 'name' property </div>  