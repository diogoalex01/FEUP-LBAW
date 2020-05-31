<div class="notification-content" href="#">

    <div class="notification-item">

        <div class="row">
            <div class="col-3">
                <a href="userProfile.php">
                    <img class="notification-pic" id="login" height="50" width="50"
                        src="{{ asset('img/avatar_female.png') }}" alt="Profile Image"></a>
            </div>
            <div class="col-7 p-0">
                <h4 class="item-title"><a>{{ $user->username }}</a> sent you a friend request</h4>
                <h6 class="item-info"> <i class="fas fa-calendar-alt"></i> 1 day ago</h6>
            </div>
            <div class="d-flex align-items-start pt-1">
                <div class="col-2">
                    <div class="row mb-3">
                        <a href="">
                            <i class="fas fa-check"></i>
                        </a>
                    </div>
                    <div class="row">
                        <a href="">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>