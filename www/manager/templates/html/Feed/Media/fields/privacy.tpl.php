<label for="privacy">Privacy</label>
<div class="dcf-popup dcf-d-inline" id="privacy-details" data-hover="true" data-point="true">
  <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
      viewBox="0 0 24 24"
    >
      <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
        S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
        C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
      <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
        C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
        S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
      <circle cx="11" cy="6.5" r="1"></circle>
      <g>
        <path fill="none" d="M0 0H24V24H0z"></path>
      </g>
    </svg>
  </button>
  <div
    class="
      dcf-popup-content
      unl-cream
      unl-bg-blue
      dcf-p-1
      dcf-rounded
    "
    style="min-width: 25ch;"
  >
      <p class="dcf-m-0 dcf-regular">
        <ul>
          <li>
            <span class="heading">Public</span> - Anyone can access the media.
          </li>
          <li>
            <span class="heading">Unlisted</span> - Media will not be included in public MediaHub listings.
          </li>
          <li>
            <span class="heading">Protected</span> - Only logged in users can access the media.
          </li>
          <li>
            <span class="heading">Private</span> - Only members of channels that the media is included in can access it.
          </li>
        </ul>
      </p>
  </div>
</div>
<select class="dcf-input-select" id="privacy" name="privacy" aria-describedby="privacy-details">
<?php
foreach (UNL_MediaHub_Media::getPossiblePrivacyValues() as $value) {
  $selected = '';
  if ($value == @$context->media->privacy) {
    $selected = 'selected="selected"';
  }
  echo "<option value='$value' " . $selected . ">" . ucfirst(strtolower($value)) . "</option>";
}
?>
</select>
