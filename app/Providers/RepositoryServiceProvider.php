namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\PackageRepository;
use App\Repositories\CityRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\PackageTypeRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(PackageRepository::class, PackageRepository::class);
        $this->app->bind(CityRepository::class, CityRepository::class);
        $this->app->bind(DistrictRepository::class, DistrictRepository::class);
        $this->app->bind(PackageTypeRepository::class, PackageTypeRepository::class);
    }
}