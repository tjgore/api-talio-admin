<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Status;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('name');
        });

        $statuses = [
            Status::PRODUCT_TYPE => Status::PRODUCT,
            Status::STORE_TYPE => Status::STORE,
            Status::ORDER_TYPE => Status::ORDER,
        ];

       foreach ($statuses as $type => $statusGroup) {
            
            foreach ($statusGroup as $id => $status) {
                Status::create([
                    'id' => $id,
                    'type' => $type,
                    'name' => $status
                ]);
            }
       }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
