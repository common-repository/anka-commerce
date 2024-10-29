import { sprintf, __ } from '@wordpress/i18n';
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { decodeEntities } from '@wordpress/html-entities';
import { getSetting } from '@woocommerce/settings';

const settings = getSetting('ankapay_data', {});

const defaultLabel = __(
  'ANKA Pay',
  'anka-commerce'
);

const label = decodeEntities(settings.title) || defaultLabel;
/**
 * Content component
 */
const Content = () => {
  return decodeEntities(settings.description || '');
};

/**
 * Icon component
 */
const Icon = () => {
  return settings.icon
    ? <img src={esc_url(settings.icon)} style={{ float: 'right' }} />
    : ''
};

/**
 * Label component
 *
 * @param {*} props Props from payment API.
 */
const Label = () => {
  return (
    <span style={{ width: '100%' }}>
      {label}
      <Icon />
    </span>
  )
};

/**
 * ANKA Pay method config object.
 */
const Anka_Pay = {
  name: "ankapay",
  label: <Label />,
  content: <Content />,
  edit: <Content />,
  icon: <Icon />,
  canMakePayment: () => true,
  ariaLabel: label,
  supports: {
    features: settings.supports,
  },
};

registerPaymentMethod(Anka_Pay);
