
<section class="pyscript">
    Hello world! <br />
    This is the current date and time, as computed by Python:
    <script type="py">
                from pyscript import display
                from datetime import datetime
                now = datetime.now()
                display(now.strftime("%m/%d/%Y, %H:%M:%S"))
            </script>
</section>












