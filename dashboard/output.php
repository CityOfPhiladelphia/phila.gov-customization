
<?php
/**
 * This file could be used to catch submitted form data. When using a non-configuration
 * view to save form data, remember to use some kind of identifying field in your form.
 */
?>
<p>Note: this is currently not functional.</p>

<form>
  <p>Select the type of alert:</p>
  <select name="alert-type">
    <option value="blue">Code Blue Effective</option>
    <option value="red">Code Red Effective</option>
    <option value="orange">Code Orange Effective</option>
    <option value="gray">Code Gray Effective</option>
    <option value="other">Generic</option>
  </select>
    <p>Enter the alert text:</p>
    <p>
        <label for="custom-alert">
            <textarea id="custom-alert" cols="50" rows="5"></textarea>
        </label>
    </p>
    <label for="from"> From: </label>
      <input type="text" id="from">
    <label for="to"> To: </label>
      <input type="text" id="to">
<h4>Should the alert currently be visible?</h4>
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
