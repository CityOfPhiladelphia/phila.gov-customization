
<?php
/**
 * This file could be used to catch submitted form data. When using a non-configuration
 * view to save form data, remember to use some kind of identifying field in your form.
 */
?>
<p>Note: this is currently not functional.</p>
<p>Enter the alert text:</p>
<form>
    <p>
        <label for="custom-alert">
            <textarea id="custom-alert" cols="50" rows="5"></textarea>
        </label>
    </p>
<h4>Should the alert be visible?</h4>
    <ul>
        <li>
            <label for="yes">
                <input type="radio" id="yes" name="alert" value="yes" />
                Yes
            </label>
        </li>
        <li>
            <label for="no">
                <input type="radio" id="no" name="alert" value="no" />
                No
            </label>
        </li>
    </ul>
    <p><button>Save</button></p>
    
</form>