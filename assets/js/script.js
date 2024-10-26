const FormHandler = 
{
    CLIENT_NUMBER_REGEX: '^000[0-9]{3}-[A-Z]{5}$',
    POLISH_IBAN_REGEX: '^PL[0-9]{26}$',
    FORM_URL: 'http://localhost:3000/index.php',
    HIDDEN_FIELD_ID: 'hiddenField',
    HIDDEN_FIELD_TRIGGER: 'choose',
    HIDDEN_FIELD_TRIGGER_VALUE: '1',
    CLIENT_NUMBER_FIELD_NAME: 'clientNumber',
    ACCOUNT_NUMBER_FIELD_NAME: 'account',
    ERRORS_CONTAINER_ID: 'errors_container',
    TABLE_CONTAINER_ID: 'table',
    SURNAME_COUNTER_ID: 'surnameCounter',
    EMAIL_COUNTER_ID: 'emailCounter',
    SORT_SELECT_ID: 'sortBy',
    SORT_HOW_SELECT_ID: 'sortHow',
    ORIGINAL_ORDER_LIST: {},
    init() {
        this.handle();
        this.hiddenFieldHandler();
        this.getList();
        this.getCounters();
        this.handleTableOrder();
    },
    handle() {
        $('#form').on('submit', (e) => {
            e.preventDefault();
            this.sendForm(this.prepareFormToSend(e.target));
        })
    },
    handleTableOrder() {
        $(`#${this.SORT_SELECT_ID}`).on('change', (e) => {
            FormHandler.getList(e.target.value);
        });

        $(`#${this.SORT_HOW_SELECT_ID}`).on('change', (e) => {
            let list = [];
            switch (e.target.value) {
                case 'asc':
                    list = FormHandler.ORIGINAL_ORDER_LIST;
                    break;
                case 'desc':
                    for (let i = FormHandler.ORIGINAL_ORDER_LIST.length - 1; i >= 0; i--) {
                        list.push(FormHandler.ORIGINAL_ORDER_LIST[i]);
                    }
                    break;
            }
            FormHandler.fillTable(list, true);
        });
    },
    validation() {
        // to do, now is only on server side
    },
    hiddenFieldHandler() {
        $(`[name=${this.HIDDEN_FIELD_TRIGGER}`).on('click', () => {
            if (this.checkRadioToShowHidenField()) {
                this.accountNumberHandler();
            } else {
                this.accountNumberHandler(false);
            }
        })
    },
    accountNumberHandler(show = true) {
        if (show) {
            $(`#${this.HIDDEN_FIELD_ID}`).removeClass('hide').find('input').attr('require', show);

        } else {
            $(`#${this.HIDDEN_FIELD_ID}`).addClass('hide').find('input').attr('require', show);

        }
    },
    checkRadioToShowHidenField() {
        return $(`[name=${this.HIDDEN_FIELD_TRIGGER}]:checked`).val() === this.HIDDEN_FIELD_TRIGGER_VALUE;
    },
    getCounters() {
        $.ajax({
            type: "GET",
            url: this.FORM_URL + '?getCounters',
            dataType: "json",
            encode: true,
          }).done(function (data) {
            if (data) {
                FormHandler.fillCounters(data);
            }
          });
    },
    getList(sortBy = null) {
        const url = sortBy ? `getList=${sortBy}` : 'getList'
        $.ajax({
            type: "GET",
            url: this.FORM_URL + '?' + url,
            dataType: "json",
            encode: true,
          }).done(function (data) {
            if (data) {
                FormHandler.fillTable(data, !!sortBy);
                FormHandler.ORIGINAL_ORDER_LIST = data;
                 $(`#${FormHandler.SORT_HOW_SELECT_ID}`).val('asc');
            }
          });
    },
    sendForm(formData) {
        $.ajax({
            type: "POST",
            url: this.FORM_URL,
            data: formData,
            dataType: "json",
            encode: true,
          }).done(function (data) {

            if (data !== 201) {
                FormHandler.showErrors(data);
            } else {
                $(`#${this.ERRORS_CONTAINER_ID}`).empty();
                const dataToFill = formData;
                dataToFill.client_no = formData.clientNumber;
                dataToFill.account_number = formData.account;
                
                FormHandler.fillTable([dataToFill]);
                FormHandler.getCounters();
                alert('Successfuly save data')
            }
          });
    },
    prepareFormToSend(data) {
        const formData = new FormData(data);
        const outPutFormData = {};
        for (const [key, value] of formData) {
            if (value) {
                if (['agreement1', 'agreement2', 'agreement3'].includes(key)) {
                    outPutFormData[key] = value === 'on' ? 1 : 0;
                } else {
                    outPutFormData[key] = value;
                }
            }
        }
        return outPutFormData;
    },
    showErrors(errors) {
        const $container =  $(`#${this.ERRORS_CONTAINER_ID}`);
        $container.empty();

        let errorsMessage = '';
        errors.forEach(error => {
           errorsMessage += `<span class="alert alert-danger" role="alert">Pole ${error.field + ': ' + error.message}</span>`;
        });

        $container.append(errorsMessage);
    },
    fillTable(data, clearTable = false) {
        const $container = $(`#${this.TABLE_CONTAINER_ID}`);

        if (clearTable) {
            $container.find('tbody').empty();
        }

        let html = '';

        data.forEach((row, index) => {
            const colorRow = index % 2 ? 'class="info"' : '';
            html += `<tr ${colorRow}>
                <td>${row.name}</td>
                <td>${row.surname}</td>
                <td>${row.email}</td>
                <td>${row.phone ?? '-'}</td> 
                <td>${row.client_no ?? '-'}</td>
                <td>${row.account_number ?? '-'}</td>
                <td>${row.choose}</td>
                <td>${row.agreement1}</td>
                <td>${row.agreement2}</td>
                <td>${row.agreement3 ?? '-'}</td>
            </tr>`;
        });

        $container.find('tbody').append(html);
    },
    fillCounters(data) {
        const $surnameCounter = $(`#${this.SURNAME_COUNTER_ID}`);
        const $emailCounter = $(`#${this.EMAIL_COUNTER_ID}`);

        $surnameCounter.empty();
        $emailCounter.empty();

        $surnameCounter.append(data.surnameCounter);
        $emailCounter.append(data.emailCounter);
    }
}

FormHandler.init();