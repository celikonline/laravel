use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type'); // success, warning, danger, info
            $table->string('link')->nullable();
            $table->boolean('is_read')->default(false);
            $table->string('notifiable_type')->nullable(); // Bildirim ilişkili model türü (Package, User, etc.)
            $table->unsignedBigInteger('notifiable_id')->nullable(); // İlişkili model ID'si
            $table->timestamps();

            // Polymorphic ilişki için index
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
}; 