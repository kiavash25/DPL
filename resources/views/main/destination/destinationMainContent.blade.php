<style>
    .mainContentHeader{
        color: #2c3e50;
        font-size: 35px;
        font-weight: bold;
    }

    .map{
        width: 100%;
        height: 50vh;
    }
    @media (max-width: 650px) {
        .mainContentPackages{
            justify-content: center;
        }

    }
</style>

@include('main.common.packageList')

<div class="row" style="margin-bottom: 100px; margin-top: 40px">
    <div class="col-md-12">
        <div id="map" class="map"></div>
    </div>
</div>
