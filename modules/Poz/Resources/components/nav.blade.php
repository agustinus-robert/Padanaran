<div class="block-transparent pull-x pull-t mb-0 block">
    <ul class="nav nav-tabs nav-tabs-block nav-justified" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="so-settings-tab" data-bs-toggle="tab" data-bs-target="#so-settings" role="tab" aria-controls="so-settings" aria-selected="true">
                <i class="fa fa-fw fa-cog"></i>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="so-people-tab" data-bs-toggle="tab" data-bs-target="#so-people" role="tab" aria-controls="so-people" aria-selected="false">
                <i class="far fa-fw fa-user-circle"></i>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="so-profile-tab" data-bs-toggle="tab" data-bs-target="#so-profile" role="tab" aria-controls="so-profile" aria-selected="false">
                <i class="far fa-fw fa-edit"></i>
            </button>
        </li>
    </ul>
    <div class="block-content tab-content overflow-hidden">
        <!-- Settings Tab -->
        <div class="tab-pane pull-x fade fade-up show active" id="so-settings" role="tabpanel" aria-labelledby="so-settings-tab" tabindex="0">
            <div class="mb-0 block">
                <!-- Color Themes -->
                <!-- Toggle Themes functionality initialized in Template._uiHandleTheme() -->
                <div class="block-content block-content-sm block-content-full bg-body">
                    <span class="text-uppercase fs-sm fw-bold">Color Themes</span>
                </div>
                <div class="block-content block-content-full">
                    <div class="row g-sm text-center">
                        <div class="col-4 mb-1">
                            <a class="d-block fs-sm fw-semibold bg-default py-3 text-white" data-toggle="theme" data-theme="default" href="#">
                                Default
                            </a>
                        </div>
                        <div class="col-4 mb-1">
                            <a class="d-block fs-sm fw-semibold bg-xwork py-3 text-white" data-toggle="theme" data-theme="assets/css/themes/xwork.min.css" href="#">
                                xWork
                            </a>
                        </div>
                        <div class="col-4 mb-1">
                            <a class="d-block fs-sm fw-semibold bg-xmodern py-3 text-white" data-toggle="theme" data-theme="assets/css/themes/xmodern.min.css" href="#">
                                xModern
                            </a>
                        </div>
                        <div class="col-4 mb-1">
                            <a class="d-block fs-sm fw-semibold bg-xeco py-3 text-white" data-toggle="theme" data-theme="assets/css/themes/xeco.min.css" href="#">
                                xEco
                            </a>
                        </div>
                        <div class="col-4 mb-1">
                            <a class="d-block fs-sm fw-semibold bg-xsmooth py-3 text-white" data-toggle="theme" data-theme="assets/css/themes/xsmooth.min.css" href="#">
                                xSmooth
                            </a>
                        </div>
                        <div class="col-4 mb-1">
                            <a class="d-block fs-sm fw-semibold bg-xinspire py-3 text-white" data-toggle="theme" data-theme="assets/css/themes/xinspire.min.css" href="#">
                                xInspire
                            </a>
                        </div>
                        <div class="col-4 mb-1">
                            <a class="d-block fs-sm fw-semibold bg-xdream py-3 text-white" data-toggle="theme" data-theme="assets/css/themes/xdream.min.css" href="#">
                                xDream
                            </a>
                        </div>
                        <div class="col-4 mb-1">
                            <a class="d-block fs-sm fw-semibold bg-xpro py-3 text-white" data-toggle="theme" data-theme="assets/css/themes/xpro.min.css" href="#">
                                xPro
                            </a>
                        </div>
                        <div class="col-4 mb-1">
                            <a class="d-block fs-sm fw-semibold bg-xplay py-3 text-white" data-toggle="theme" data-theme="assets/css/themes/xplay.min.css" href="#">
                                xPlay
                            </a>
                        </div>
                        <div class="col-12">
                            <a class="d-block bg-body-dark fw-semibold text-dark py-3" href="be_ui_color_themes.html">All
                                Color Themes</a>
                        </div>
                    </div>
                </div>
                <!-- END Color Themes -->

                <!-- Sidebar -->
                <div class="block-content block-content-sm block-content-full bg-body">
                    <span class="text-uppercase fs-sm fw-bold">Sidebar</span>
                </div>
                <div class="block-content block-content-full">
                    <div class="row g-sm text-center">
                        <div class="col-6 mb-1">
                            <a class="d-block bg-body-dark fw-semibold text-dark py-3" data-toggle="layout" data-action="sidebar_style_dark" href="javascript:void(0)">Dark</a>
                        </div>
                        <div class="col-6 mb-1">
                            <a class="d-block bg-body-dark fw-semibold text-dark py-3" data-toggle="layout" data-action="sidebar_style_light" href="javascript:void(0)">Light</a>
                        </div>
                    </div>
                </div>
                <!-- END Sidebar -->

                <!-- Header -->
                <div class="block-content block-content-sm block-content-full bg-body">
                    <span class="text-uppercase fs-sm fw-bold">Header</span>
                </div>
                <div class="block-content block-content-full">
                    <div class="row g-sm mb-2 text-center">
                        <div class="col-6 mb-1">
                            <a class="d-block bg-body-dark fw-semibold text-dark py-3" data-toggle="layout" data-action="header_style_dark" href="javascript:void(0)">Dark</a>
                        </div>
                        <div class="col-6 mb-1">
                            <a class="d-block bg-body-dark fw-semibold text-dark py-3" data-toggle="layout" data-action="header_style_light" href="javascript:void(0)">Light</a>
                        </div>
                        <div class="col-6 mb-1">
                            <a class="d-block bg-body-dark fw-semibold text-dark py-3" data-toggle="layout" data-action="header_mode_fixed" href="javascript:void(0)">Fixed</a>
                        </div>
                        <div class="col-6 mb-1">
                            <a class="d-block bg-body-dark fw-semibold text-dark py-3" data-toggle="layout" data-action="header_mode_static" href="javascript:void(0)">Static</a>
                        </div>
                    </div>
                </div>
                <!-- END Header -->

                <!-- Content -->
                <div class="block-content block-content-sm block-content-full bg-body">
                    <span class="text-uppercase fs-sm fw-bold">Content</span>
                </div>
                <div class="block-content block-content-full">
                    <div class="row g-sm text-center">
                        <div class="col-6 mb-1">
                            <a class="d-block bg-body-dark fw-semibold text-dark py-3" data-toggle="layout" data-action="content_layout_boxed" href="javascript:void(0)">Boxed</a>
                        </div>
                        <div class="col-6 mb-1">
                            <a class="d-block bg-body-dark fw-semibold text-dark py-3" data-toggle="layout" data-action="content_layout_narrow" href="javascript:void(0)">Narrow</a>
                        </div>
                        <div class="col-12 mb-1">
                            <a class="d-block bg-body-dark fw-semibold text-dark py-3" data-toggle="layout" data-action="content_layout_full_width" href="javascript:void(0)">Full Width</a>
                        </div>
                    </div>
                </div>
                <!-- END Content -->

                <!-- Layout API -->
                <div class="block-content block-content-full border-top">
                    <a class="btn w-100 btn-alt-primary" href="be_layout_api.html">
                        <i class="fa fa-fw fa-flask me-1"></i> Layout API
                    </a>
                </div>
                <!-- END Layout API -->
            </div>
        </div>
        <!-- END Settings Tab -->

        <!-- People -->
        <div class="tab-pane pull-x fade fade-up" id="so-people" role="tabpanel" aria-labelledby="so-people-tab" tabindex="0">
            <div class="mb-0 block">
                <!-- Online -->
                <div class="block-content block-content-sm block-content-full bg-body">
                    <span class="text-uppercase fs-sm fw-bold">Online</span>
                </div>
                <div class="block-content">
                    <ul class="nav-items">
                        <li>
                            <a class="d-flex py-2" href="be_pages_generic_profile.html">
                                <div class="overlay-container mx-3 flex-shrink-0">
                                    <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar4.jpg" alt="">
                                    <span class="overlay-item item item-tiny item-circle bg-success border border-2 border-white"></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Carol Ray</div>
                                    <div class="fs-sm text-muted">Photographer</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="d-flex py-2" href="be_pages_generic_profile.html">
                                <div class="overlay-container mx-3 flex-shrink-0">
                                    <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar16.jpg" alt="">
                                    <span class="overlay-item item item-tiny item-circle bg-success border border-2 border-white"></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Thomas Riley</div>
                                    <div class="fw-normal fs-sm text-muted">Web Designer</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="d-flex py-2" href="be_pages_generic_profile.html">
                                <div class="overlay-container mx-3 flex-shrink-0">
                                    <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar2.jpg" alt="">
                                    <span class="overlay-item item item-tiny item-circle bg-success border border-2 border-white"></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Carol White</div>
                                    <div class="fw-normal fs-sm text-muted">Web Developer</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Online -->

                <!-- Busy -->
                <div class="block-content block-content-sm block-content-full bg-body">
                    <span class="text-uppercase fs-sm fw-bold">Busy</span>
                </div>
                <div class="block-content">
                    <ul class="nav-items">
                        <li>
                            <a class="d-flex py-2" href="be_pages_generic_profile.html">
                                <div class="overlay-container mx-3 flex-shrink-0">
                                    <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar1.jpg" alt="">
                                    <span class="overlay-item item item-tiny item-circle bg-danger border border-2 border-white"></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Melissa Rice</div>
                                    <div class="fw-normal fs-sm text-muted">UI Designer</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- END Busy -->

                <!-- Away -->
                <div class="block-content block-content-sm block-content-full bg-body">
                    <span class="text-uppercase fs-sm fw-bold">Away</span>
                </div>
                <div class="block-content">
                    <ul class="nav-items">
                        <li>
                            <a class="d-flex py-2" href="be_pages_generic_profile.html">
                                <div class="overlay-container mx-3 flex-shrink-0">
                                    <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar13.jpg" alt="">
                                    <span class="overlay-item item item-tiny item-circle bg-warning border border-2 border-white"></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Ralph Murray</div>
                                    <div class="fw-normal fs-sm text-muted">Copywriter</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="d-flex py-2" href="be_pages_generic_profile.html">
                                <div class="overlay-container mx-3 flex-shrink-0">
                                    <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar1.jpg" alt="">
                                    <span class="overlay-item item item-tiny item-circle bg-warning border border-2 border-white"></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Sara Fields</div>
                                    <div class="fw-normal fs-sm text-muted">Writer</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- END Away -->

                <!-- Offline -->
                <div class="block-content block-content-sm block-content-full bg-body">
                    <span class="text-uppercase fs-sm fw-bold">Offline</span>
                </div>
                <div class="block-content">
                    <ul class="nav-items">
                        <li>
                            <a class="d-flex py-2" href="be_pages_generic_profile.html">
                                <div class="overlay-container mx-3 flex-shrink-0">
                                    <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar14.jpg" alt="">
                                    <span class="overlay-item item item-tiny item-circle bg-muted border border-2 border-white"></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Brian Stevens</div>
                                    <div class="fw-normal fs-sm text-muted">Teacher</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="d-flex py-2" href="be_pages_generic_profile.html">
                                <div class="overlay-container mx-3 flex-shrink-0">
                                    <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar8.jpg" alt="">
                                    <span class="overlay-item item item-tiny item-circle bg-muted border border-2 border-white"></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Helen Jacobs</div>
                                    <div class="fw-normal fs-sm text-muted">Photographer</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="d-flex py-2" href="be_pages_generic_profile.html">
                                <div class="overlay-container mx-3 flex-shrink-0">
                                    <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar4.jpg" alt="">
                                    <span class="overlay-item item item-tiny item-circle bg-muted border border-2 border-white"></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Carol Ray</div>
                                    <div class="fw-normal fs-sm text-muted">Front-end Developer</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="d-flex py-2" href="be_pages_generic_profile.html">
                                <div class="overlay-container mx-3 flex-shrink-0">
                                    <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar13.jpg" alt="">
                                    <span class="overlay-item item item-tiny item-circle bg-muted border border-2 border-white"></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">David Fuller</div>
                                    <div class="fw-normal fs-sm text-muted">UX Specialist</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- END Offline -->

                <!-- Add People -->
                <div class="block-content block-content-full border-top">
                    <a class="btn w-100 btn-alt-primary" href="javascript:void(0)">
                        <i class="fa fa-fw fa-plus me-1 opacity-50"></i> Add People
                    </a>
                </div>
                <!-- END Add People -->
            </div>
        </div>
        <!-- END People -->

        <!-- Profile -->
        <div class="tab-pane pull-x fade fade-up" id="so-profile" role="tabpanel" aria-labelledby="so-profile-tab" tabindex="0">
            <form action="be_pages_dashboard.html" method="POST" onsubmit="return false;">
                <div class="mb-0 block">
                    <!-- Personal -->
                    <div class="block-content block-content-sm block-content-full bg-body">
                        <span class="text-uppercase fs-sm fw-bold">Personal</span>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="mb-4">
                            <label class="form-label">Username</label>
                            <input type="text" readonly class="form-control" id="so-profile-username-static" value="Admin">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="so-profile-name">Name</label>
                            <input type="text" class="form-control" id="so-profile-name" name="so-profile-name" value="George Taylor">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="so-profile-email">Email</label>
                            <input type="email" class="form-control" id="so-profile-email" name="so-profile-email" value="g.taylor@example.com">
                        </div>
                    </div>
                    <!-- END Personal -->

                    <!-- Password Update -->
                    <div class="block-content block-content-sm block-content-full bg-body">
                        <span class="text-uppercase fs-sm fw-bold">Password Update</span>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="mb-4">
                            <label class="form-label" for="so-profile-password">Current Password</label>
                            <input type="password" class="form-control" id="so-profile-password" name="so-profile-password">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="so-profile-new-password">New Password</label>
                            <input type="password" class="form-control" id="so-profile-new-password" name="so-profile-new-password">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="so-profile-new-password-confirm">Confirm New Password</label>
                            <input type="password" class="form-control" id="so-profile-new-password-confirm" name="so-profile-new-password-confirm">
                        </div>
                    </div>
                    <!-- END Password Update -->

                    <!-- Options -->
                    <div class="block-content block-content-sm block-content-full bg-body">
                        <span class="text-uppercase fs-sm fw-bold">Options</span>
                    </div>
                    <div class="block-content">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="so-settings-status" name="so-settings-status">
                            <label class="form-check-label fw-semibold" for="so-settings-status">Online Status</label>
                        </div>
                        <p class="text-muted fs-sm">
                            Make your online status visible to other users of your app
                        </p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="so-settings-notifications" name="so-settings-notifications">
                            <label class="form-check-label fw-semibold" for="so-settings-notifications">Notifications</label>
                        </div>
                        <p class="text-muted fs-sm">
                            Receive desktop notifications regarding your projects and sales
                        </p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="so-settings-updates" name="so-settings-updates">
                            <label class="form-check-label fw-semibold" for="so-settings-updates">Auto Updates</label>
                        </div>
                        <p class="text-muted fs-sm">
                            If enabled, we will keep all your applications and servers up to date with the most recent
                            features automatically
                        </p>
                    </div>
                    <!-- END Options -->

                    <!-- Submit -->
                    <div class="block-content block-content-full border-top">
                        <button type="submit" class="btn w-100 btn-alt-primary">
                            <i class="fa fa-fw fa-save me-1 opacity-50"></i> Save
                        </button>
                    </div>
                    <!-- END Submit -->
                </div>
            </form>
        </div>
        <!-- END Profile -->
    </div>
</div>
