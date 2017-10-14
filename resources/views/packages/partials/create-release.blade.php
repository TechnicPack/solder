<div class="box">
    <h1>Add Release</h1>
    <div class="box-body">
        <form action="/library/{{ $package->slug }}/releases" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Version</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control has-icons-left is-expanded">
                            <input class="input" type="text" placeholder="1.2.3" name="version">
                            <span class="icon is-small is-left">
                                        <i class="fa fa-code-fork"></i>
                                    </span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">File</label>
                </div>
                <div class="field-body">
                    <div class="file control">
                        <label class="file-label">
                            <input class="file-input" type="file" name="archive">
                            <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="fa fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                            Choose a file…
                                        </span>
                                    </span>

                        </label>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label">
                    &nbsp;
                </div>
                <div class="field-body">
                    <div class="control">
                        <button class="button is-primary" type="submit">Add Release</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
