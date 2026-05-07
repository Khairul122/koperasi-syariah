<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title><?= $page_title ?? 'Synectra Panel' ?></title>

  <!-- Google Fonts (Plus Jakarta Sans) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  
  <!-- Material Design Icons -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendors/mdi/css/materialdesignicons.min.css" />

  <!-- Neobrutalism Theme -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/neobrutalism.css" />

  <!-- SynAlert Component -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components/alert-dialog.css" />
  
  <link rel="shortcut icon" href="<?= BASE_URL ?>/assets/images/favicon.png" />

  <style>
    :root {
        --navbar-height: 70px;
    }

    .container-scroller {
        display: flex;
        min-height: 100vh;
    }

    .main-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
        transition: all 0.3s ease;
    }

    .content-wrapper {
        padding: 1.5rem;
        flex: 1;
    }
  </style>
  <style>
    /* Compatibility: map legacy form classes to NeoBrutalism theme (shadcn input mapping) */
    .page-header-simple { display:flex;align-items:center;justify-content:space-between;padding:1rem 1.5rem;background:var(--neo-bg);border-bottom:3px solid #000; }
    .page-header-simple h3{margin:0;font-weight:900;text-transform:uppercase}
    .form-section{background:#fff;border-radius:12px;border:3px solid #000;box-shadow:6px 6px 0 #000;padding:1.25rem;margin-bottom:1.25rem}
    .form-section-header{background:#FFD600;border-bottom:3px solid #000;padding:0.75rem 1rem;display:flex;align-items:center;justify-content:space-between}

    /* Input styles (Neobrutalism from shadcn input component) */
    .form-control {
      display:block;
      width:100%;
      height:auto;
      min-height:40px;
      border-radius:8px;
      border:3px solid #000;
      background: #fff;
      padding:0.75rem;
      font-size:0.875rem;
      font-weight:500;
      color: #000;
      font-family:inherit;
      box-shadow: 4px 4px 0 rgba(0,0,0,0.1);
      transition: all 0.2s ease;
    }
    .form-control::placeholder { color: rgba(0,0,0,0.5); font-weight:400; }
    .form-control:focus {
      outline: none;
      border-color: #000;
      box-shadow: 4px 4px 0 #000, inset 0 0 0 2px #FFD600;
    }
    .form-control:disabled { cursor:not-allowed; opacity:0.6; background:#f0f0f0; }
    textarea.form-control { resize:vertical; min-height:100px; }
    input[type="file"].form-control { padding:0.5rem; cursor:pointer; }
    
    /* Form group & label styling */
    .form-group { margin-bottom:1.5rem; }
    .form-label { 
      display:block;
      margin-bottom:0.5rem;
      font-weight:700;
      text-transform:uppercase;
      font-size:0.75rem;
      letter-spacing:0.05em;
      color:#000;
    }
    .form-label .required { color:#FF4081; font-weight:900; margin-left:0.25rem; }
    
    /* Checkbox styling */
    .form-check-input, .ui-checkbox {
      width:20px;
      height:20px;
      border:3px solid #000;
      border-radius:4px;
      background:#fff;
      cursor:pointer;
      accent-color:#FFD600;
    }
    .form-check-input:focus { outline:none; box-shadow:0 0 0 3px #FFD600, 0 0 0 5px #000; }
    
    /* Form text helper */
    .form-text { 
      display:block;
      margin-top:0.25rem;
      font-size:0.75rem;
      color:#666;
      font-weight:400;
    }

    /* Select/dropdown styling */
    select.form-control, .form-select {
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23000' d='M10.293 3.293L6 7.586 1.707 3.293A1 1 0 00.293 4.707l5 5a1 1 0 001.414 0l5-5a1 1 0 10-1.414-1.414z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 12px;
      padding-right: 2.5rem;
    }

    /* Small helpers to make legacy buttons match neo style */
    .neo-btn, .btn.neo-btn-white { background:#fff;border:3px solid #000;color:inherit;box-shadow:4px 4px 0 #000; padding:0.75rem 1.5rem; border-radius:8px; font-weight:700; cursor:pointer; transition:all 0.2s ease; }
    .neo-btn:hover, .btn.neo-btn-white:hover { transform:translate(-2px,-2px); box-shadow:6px 6px 0 #000; }
    .neo-btn.neo-btn-primary { background:#000;color:#fff;border:3px solid #000; box-shadow:4px 4px 0 #000; }
    .neo-btn.neo-btn-primary:hover { background:#1a1a1a; transform:translate(-2px,-2px); box-shadow:6px 6px 0 #000; }
  </style>
</head>
