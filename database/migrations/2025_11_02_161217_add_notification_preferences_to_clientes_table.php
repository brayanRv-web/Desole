<?php
// database/migrations/2025_01_01_000002_add_notification_preferences_to_clientes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            // ✅ CAMPOS ESPECÍFICOS DE PERMISOS
            $table->boolean('recibir_whatsapp')->default(true);
            $table->boolean('recibir_email')->default(false);
            $table->boolean('recibir_sms')->default(false);
            
            // ✅ FECHA DE CONSENTIMIENTO (importante para LGPD)
            $table->timestamp('consentimiento_notificaciones')->nullable();
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn([
                'recibir_whatsapp',
                'recibir_email', 
                'recibir_sms',
                'consentimiento_notificaciones'
            ]);
        });
    }
};